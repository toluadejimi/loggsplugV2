import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { apiGet } from '../api/client'
import { Card, CardContent } from '../components/ui/card'
import { Button } from '../components/ui/button'
import type { Category, Product } from '../types/api'

interface CategoryProductsResponse {
  category: Category
  products: { data: Product[] }
}

export default function CategoryProducts() {
  const { id } = useParams<{ id: string }>()
  const [data, setData] = useState<CategoryProductsResponse | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    if (!id) return
    apiGet<CategoryProductsResponse>(`/api/category-products/${id}`)
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false))
  }, [id])

  if (loading || !data) {
    return (
      <div className="container max-w-4xl mx-auto py-8">
        {loading ? 'Loading…' : 'Category not found.'}
      </div>
    )
  }

  const products = data.products?.data ?? data.products ?? []
  const list = Array.isArray(products) ? products : []

  return (
    <div className="container max-w-4xl mx-auto py-6 px-4">
      <Link to="/products" className="text-sm text-primary hover:underline mb-4 inline-block">
        ← All products
      </Link>
      <h1 className="text-2xl font-bold mb-6">{data.category.name}</h1>
      <div className="grid gap-3 sm:grid-cols-2">
        {list.map((product: Product) => (
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
                  {product.in_stock} in stock · ₦{Number(product.price).toLocaleString()}
                </p>
                <Button size="sm" className="mt-2" asChild>
                  <Link to={`/product/${product.id}`}>View</Link>
                </Button>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
