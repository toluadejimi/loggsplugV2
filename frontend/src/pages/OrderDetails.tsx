import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { apiGet } from '../api/client'
import { Card, CardContent } from '../components/ui/card'
import type { Order, OrderItem } from '../types/api'

interface OrderDetailsResponse {
  order: Order
}

export default function OrderDetails() {
  const { id } = useParams<{ id: string }>()
  const [order, setOrder] = useState<Order | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    if (!id) return
    apiGet<OrderDetailsResponse>(`/api/orders/${id}`)
      .then((r) => setOrder(r.order))
      .catch(() => setOrder(null))
      .finally(() => setLoading(false))
  }, [id])

  if (loading || !order) {
    return (
      <div className="container max-w-4xl mx-auto py-8">
        {loading ? 'Loading…' : 'Order not found.'}
      </div>
    )
  }

  const items = order.order_items ?? order.orderItems ?? []

  return (
    <div className="container max-w-4xl mx-auto py-6 px-4">
      <Link to="/orders" className="text-sm text-primary hover:underline mb-4 inline-block">
        ← Back to orders
      </Link>
      <h1 className="text-2xl font-bold mb-6">Order #{order.id}</h1>
      <Card className="mb-6">
        <CardContent className="p-4">
          <p className="text-muted-foreground">
            Total: ₦{Number(order.total_amount).toLocaleString()} ·{' '}
            {new Date(order.created_at).toLocaleString()}
          </p>
          {order.deposit?.trx && (
            <p className="text-sm font-mono mt-1">Trx: {order.deposit.trx}</p>
          )}
        </CardContent>
      </Card>
      <h2 className="font-semibold mb-3">Items</h2>
      <div className="space-y-2">
        {items.map((item: OrderItem) => (
          <Card key={item.id}>
            <CardContent className="p-4">
              <p className="font-medium">{item.product?.name ?? 'Product'}</p>
              {item.product_detail?.details && (
                <pre className="text-sm text-muted-foreground mt-2 whitespace-pre-wrap font-sans">
                  {item.product_detail.details}
                </pre>
              )}
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
