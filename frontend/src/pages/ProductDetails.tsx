import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { apiGet } from '../api/client'
import { Button } from '../components/ui/button'
import { Card, CardContent } from '../components/ui/card'
import type { Product } from '../types/api'

interface ProductResponse {
  product: Product
  related_products: Product[]
}

export default function ProductDetails() {
  const { id } = useParams<{ id: string }>()
  const [data, setData] = useState<ProductResponse | null>(null)
  const [loading, setLoading] = useState(true)
  useEffect(() => {
    if (!id) return
    apiGet<ProductResponse>(`/api/products/${id}`)
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false))
  }, [id])

  if (loading || !data) {
    return (
      <div className="container max-w-4xl mx-auto py-8">
        {loading ? 'Loading…' : 'Product not found.'}
      </div>
    )
  }

  const { product, related_products } = data

  return (
    <div className="container max-w-4xl mx-auto py-6 px-4">
      <div className="grid gap-8 md:grid-cols-2 mb-8">
        <img
          src={`/assets/images/product/${product.image ?? 'default.png'}`}
          alt={product.name}
          className="rounded-xl border w-full aspect-square object-cover"
        />
        <div>
          <h1 className="text-2xl font-bold mb-2">{product.name}</h1>
          {product.description && (
            <p className="text-muted-foreground mb-4 whitespace-pre-wrap">{product.description}</p>
          )}
          <p className="text-lg font-semibold mb-2">
            ₦{Number(product.price).toLocaleString()} · {product.in_stock} in stock
          </p>
          <Button asChild>
            <a href={`/product/details/${product.id}`}>View & buy (checkout on server)</a>
          </Button>
        </div>
      </div>

      {related_products.length > 0 && (
        <section>
          <h2 className="text-lg font-semibold mb-3">Related</h2>
          <div className="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
            {related_products.map((p) => (
              <Card key={p.id}>
                <CardContent className="p-4">
                  <Link to={`/product/${p.id}`} className="font-medium hover:underline block">
                    {p.name}
                  </Link>
                  <p className="text-sm text-muted-foreground">{p.in_stock} in stock</p>
                  <Button size="sm" className="mt-2" asChild>
                    <Link to={`/product/${p.id}`}>View</Link>
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </section>
      )}
    </div>
  )
}
