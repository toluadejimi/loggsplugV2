import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { apiGet } from '../api/client'
import { Card, CardContent } from '../components/ui/card'
import type { Order } from '../types/api'

interface OrdersResponse {
  orders: { data: Order[] }
  count_order: number
  order_sum: number
}

export default function Orders() {
  const [data, setData] = useState<OrdersResponse | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    apiGet<OrdersResponse>('/api/orders')
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false))
  }, [])

  if (loading) {
    return (
      <div className="container max-w-4xl mx-auto py-8">Loading orders…</div>
    )
  }

  const orders = data?.orders?.data ?? data?.orders ?? []
  const list = Array.isArray(orders) ? orders : []
  const count = data?.count_order ?? 0
  const sum = data?.order_sum ?? 0

  return (
    <div className="container max-w-4xl mx-auto py-6 px-4">
      <h1 className="text-2xl font-bold mb-2">Orders</h1>
      <p className="text-muted-foreground mb-6">
        {count} order(s) · ₦{Number(sum).toLocaleString()} total
      </p>
      {list.length === 0 ? (
        <Card>
          <CardContent className="py-8 text-center text-muted-foreground">
            No orders yet. <Link to="/products" className="text-primary hover:underline">Browse products</Link>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-4">
          {list.map((order: Order) => (
            <Card key={order.id}>
              <CardContent className="p-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                  <p className="font-medium">Order #{order.id}</p>
                  <p className="text-sm text-muted-foreground">
                    ₦{Number(order.total_amount).toLocaleString()} · {new Date(order.created_at).toLocaleDateString()}
                  </p>
                </div>
                <Link
                  to={`/orders/${order.id}`}
                  className="text-primary text-sm font-medium hover:underline"
                >
                  View details →
                </Link>
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  )
}
