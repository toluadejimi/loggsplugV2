import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { apiGet } from '../api/client'
import { Button } from '../components/ui/button'
import { Input } from '../components/ui/input'
import { Card, CardContent } from '../components/ui/card'
import type { Category, Product } from '../types/api'
import { useAuth } from '../contexts/AuthContext'

interface ProductsResponse {
  categories: { data: Category[]; current_page: number; last_page: number; next_page_url: string | null }
  categories_drop: Category[]
  bought: { user_name: string; item: string; amount: number; created_at: string }[]
  search: string | null
}

export default function Products() {
  const [data, setData] = useState<ProductsResponse | null>(null)
  const [search, setSearch] = useState('')
  const [submitSearch, setSubmitSearch] = useState('')
  const [loading, setLoading] = useState(true)
  const { user } = useAuth()

  useEffect(() => {
    const params = new URLSearchParams()
    if (submitSearch) params.set('search', submitSearch)
    apiGet<ProductsResponse>(`/api/products?${params}`)
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false))
  }, [submitSearch])

  if (loading) {
    return (
      <div className="container max-w-4xl mx-auto py-8 flex justify-center">
        <p className="text-muted-foreground">Loading products…</p>
      </div>
    )
  }

  const raw = data?.categories
  const categoriesArray = Array.isArray(raw) ? raw : (raw?.data ?? [])

  return (
    <div className="container max-w-4xl mx-auto py-6 px-4">
      <div className="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center mb-6">
        <div>
          <h1 className="text-2xl font-bold">
            Hi{user?.username ? `, ${user.username}` : ''} 👋
          </h1>
          <p className="text-muted-foreground text-sm">Browse categories or search below.</p>
        </div>
        <form
          className="flex gap-2 w-full sm:w-auto"
          onSubmit={(e) => {
            e.preventDefault()
            setSubmitSearch(search.trim())
          }}
        >
          <Input
            placeholder="Search products…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="max-w-[200px]"
          />
          <Button type="submit">Search</Button>
        </form>
      </div>

      <div className="space-y-8">
        {categoriesArray.map((cat: Category & { products?: Product[] }) => (
          <section key={cat.id}>
            <div className="flex justify-between items-center mb-3">
              <h2 className="text-lg font-semibold">{cat.name}</h2>
              <Link
                to={`/category/${cat.id}`}
                className="text-sm text-primary hover:underline"
              >
                View all →
              </Link>
            </div>
            <div className="grid gap-3 sm:grid-cols-2">
              {(cat.products ?? []).slice(0, 5).map((product: Product) => (
                <Card key={product.id}>
                  <CardContent className="p-4 flex flex-row gap-4">
                    <img
                      src={`/assets/images/product/${product.image ?? 'default.png'}`}
                      alt=""
                      className="w-16 h-16 rounded object-cover"
                    />
                    <div className="flex-1 min-w-0">
                      <Link
                        to={`/product/${product.id}`}
                        className="font-medium text-foreground hover:underline block truncate"
                      >
                        {product.name}
                      </Link>
                      <p className="text-sm text-muted-foreground">
                        {product.in_stock} in stock
                        · ₦{Number(product.price).toLocaleString()}
                      </p>
                      <Button size="sm" className="mt-2" asChild>
                        <Link to={`/product/${product.id}`}>View</Link>
                      </Button>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          </section>
        ))}
      </div>
    </div>
  )
}
