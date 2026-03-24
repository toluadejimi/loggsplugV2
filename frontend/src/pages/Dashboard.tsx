import { useEffect, useMemo, useRef, useState } from 'react'
import { Link, useOutletContext } from 'react-router-dom'
import { apiGet } from '../api/client'
import type { Category, Product, DashboardWidget } from '../types/api'

interface DashboardApiResponse {
  widget: DashboardWidget
}

interface ProductsResponse {
  categories: {
    data: (Category & { products?: Product[] })[]
    current_page?: number
    last_page?: number
    next_page_url?: string | null
  }
}

export default function Dashboard() {
  const { search } = useOutletContext<{ search: string; theme: 'dark' | 'light' }>()
  const [widget, setWidget] = useState<DashboardWidget | null>(null)
  const [categories, setCategories] = useState<(Category & { products?: Product[] })[]>([])
  const [loading, setLoading] = useState(true)
  const [activeCategoryId, setActiveCategoryId] = useState<string>('all')
  const [visibleProductsByCategory, setVisibleProductsByCategory] = useState<Record<string, number>>({})
  const [isAutoLoadingMore, setIsAutoLoadingMore] = useState(false)
  const loadMoreRef = useRef<HTMLDivElement | null>(null)

  const PRODUCTS_INITIAL = 5
  const PRODUCTS_STEP = 5

  useEffect(() => {
    const load = async () => {
      try {
        const w = await apiGet<DashboardApiResponse>('/api/dashboard').then((r) => r.widget)

        // Fetch ALL paginated categories from /api/products
        let page = 1
        const all: (Category & { products?: Product[] })[] = []
        // Safety cap to avoid infinite loops if backend misbehaves
        for (let i = 0; i < 50; i += 1) {
          const r = await apiGet<ProductsResponse>(`/api/products?page=${page}`)
          const batch = r?.categories?.data ?? []
          all.push(...batch)
          const nextUrl = r?.categories?.next_page_url
          if (!nextUrl) break
          page += 1
        }

        const cats = [...all].sort((a, b) => {
          const ap = (a.products ?? []).length
          const bp = (b.products ?? []).length
          if (ap === 0 && bp > 0) return 1
          if (bp === 0 && ap > 0) return -1
          return a.name.localeCompare(b.name)
        })

        setWidget(w ?? null)
        setCategories(cats)
      } catch {
        // ignore
      } finally {
        setLoading(false)
      }
    }

    load()
  }, [])

  useEffect(() => {
    // Reset visible product counts after categories load
    const initial: Record<string, number> = {}
    for (const c of categories) initial[String(c.id)] = PRODUCTS_INITIAL
    setVisibleProductsByCategory(initial)
  }, [categories])

  const totalOrders = widget?.total_orders ?? 0
  const q = (search || '').trim().toLowerCase()

  const filteredCategories = useMemo(() => {
    const base =
      activeCategoryId === 'all' ? categories : categories.filter((c) => String(c.id) === String(activeCategoryId))

    return base
      .map((c) => {
        if (!q) return c
        const products = (c.products ?? []).filter((p) => {
          const hay = `${p.name ?? ''} ${p.description ?? ''}`.toLowerCase()
          return hay.includes(q)
        })
        return { ...c, products }
      })
      // Only hide empty categories when searching
      .filter((c) => (!q ? true : (c.products ?? []).length > 0))
  }, [activeCategoryId, categories, q])

  useEffect(() => {
    const el = loadMoreRef.current
    if (!el) return

    const observer = new IntersectionObserver(
      (entries) => {
        const entry = entries[0]
        if (!entry?.isIntersecting) return

        // If everything already fully visible, do nothing
        const hasMore = filteredCategories.some((c) => {
          const total = c.products?.length ?? 0
          const visible = visibleProductsByCategory[String(c.id)] ?? PRODUCTS_INITIAL
          return visible < total
        })
        if (!hasMore) return

        setIsAutoLoadingMore(true)
        // Next tick so UI can show loader
        setTimeout(() => {
          setVisibleProductsByCategory((prev) => {
            const next: Record<string, number> = { ...prev }
            for (const c of filteredCategories) {
              const id = String(c.id)
              const total = c.products?.length ?? 0
              const visible = next[id] ?? PRODUCTS_INITIAL
              next[id] = Math.min(total, visible + PRODUCTS_STEP)
            }
            return next
          })
          setIsAutoLoadingMore(false)
        }, 150)
      },
      { root: null, rootMargin: '600px 0px', threshold: 0 }
    )

    observer.observe(el)
    return () => observer.disconnect()
  }, [filteredCategories, visibleProductsByCategory])

  if (loading) {
    return (
      <div style={{ padding: 40, textAlign: 'center', color: 'hsl(var(--db-text-muted))' }}>
        Loading…
      </div>
    )
  }

  return (
    <div className="categories-page">
      <div className="categories-page-header">
        <div className="categories-page-title">Explore products</div>
        <div className="categories-page-subtitle">
          {q ? `Results for "${search}"` : 'Choose a category to see more.'} · {totalOrders} orders
        </div>

        <div className="category-filters">
          <span className="filter-label">Category:</span>
          <select className="filter-select" value={activeCategoryId} onChange={(e) => setActiveCategoryId(e.target.value)}>
            <option value="all">All</option>
            {categories.map((c) => (
              <option key={c.id} value={String(c.id)}>
                {c.name}
              </option>
            ))}
          </select>
        </div>
      </div>

      {filteredCategories.length === 0 ? (
        <div className="categories-empty">
          <div className="categories-empty-icon">📦</div>
          <div className="categories-empty-title">{q ? 'No products match your search.' : 'No Products Available'}</div>
          <div className="categories-empty-desc">
            {q ? 'Try another keyword or clear the search.' : 'Check back later for new products.'}
          </div>
        </div>
      ) : (
        filteredCategories.map((cat) => (
          <div key={cat.id} className="category-block">
            <div className="category-header">
              <div className="cat-head-left">
                <div className="cat-platform-icon">{cat.name.charAt(0).toUpperCase()}</div>
                <div className="cat-title">{cat.name}</div>
              </div>
              <Link to={`/category/${cat.id}`} className="cat-see-more">
                View all →
              </Link>
            </div>

            <div className="category-detail-list product-list-wrap">
              <div className="product-list">
                {(cat.products ?? []).slice(
                  0,
                  visibleProductsByCategory[String(cat.id)] ?? PRODUCTS_INITIAL
                ).map((product: Product) => {
                  const stockClass = product.in_stock === 0 ? 'zero' : product.in_stock < 10 ? 'low' : ''
                  const priceText = `N${Number(product.price).toLocaleString()}`

                  return (
                    <div key={product.id} className="account-row">
                      <div className="acc-platform-icon">
                        <img
                          src={`/assets/images/product/${product.image ?? 'default.png'}`}
                          alt=""
                          width={44}
                          height={44}
                          style={{ borderRadius: 12, objectFit: 'cover' }}
                          loading="lazy"
                          onError={(e) => {
                            e.currentTarget.src = '/assets/images/product/default.png'
                          }}
                        />
                      </div>

                      <div className="acc-content">
                        <div className="acc-info">
                          <div className="acc-desc-title">{product.name}</div>
                          <div className="acc-desc">{product.description ?? `${product.in_stock} in stock`}</div>
                        </div>

                        <div className="acc-meta-row">
                          <div className="acc-stock-price">
                            <span className={`stock-pill ${stockClass}`}>{product.in_stock}</span>
                            <span className="price-pill">{priceText}</span>
                          </div>

                          {product.in_stock > 0 ? (
                            <Link
                              to={`/product/${product.id}`}
                              className="buy-btn buy-btn-icon"
                              aria-label={`View ${product.name}`}
                            >
                              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                              </svg>
                            </Link>
                          ) : (
                            <button type="button" className="buy-btn buy-btn-icon" disabled aria-label="Out of stock">
                              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      </div>
                    </div>
                  )
                })}
              </div>

              {(() => {
                const total = cat.products?.length ?? 0
                const visible = visibleProductsByCategory[String(cat.id)] ?? PRODUCTS_INITIAL
                if (total <= visible) return null
                return (
                  <div style={{ paddingTop: 12 }}>
                    <button
                      type="button"
                      className="cat-see-more"
                      onClick={() =>
                        setVisibleProductsByCategory((prev) => ({
                          ...prev,
                          [String(cat.id)]: visible + PRODUCTS_STEP,
                        }))
                      }
                    >
                      Load more
                    </button>
                  </div>
                )
              })()}
            </div>
          </div>
        ))
      )}

      {/* Infinite scroll sentinel */}
      <div ref={loadMoreRef} style={{ height: 1 }} />
      {isAutoLoadingMore && (
        <div style={{ padding: '14px 0 6px', textAlign: 'center', color: 'hsl(var(--db-text-muted))', fontSize: 13 }}>
          Loading more…
        </div>
      )}
    </div>
  )
}
