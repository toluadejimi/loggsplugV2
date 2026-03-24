export interface User {
  id: number
  username: string
  email: string
  firstname?: string
  lastname?: string
  address?: Record<string, string>
  wallet?: { balance: number }
}

export interface Category {
  id: number
  name: string
}

export interface Product {
  id: number
  name: string
  description?: string
  price: number
  image?: string
  category_id: number
  in_stock: number
  category?: Category
  product_details?: unknown[]
}

export interface ProductSlider {
  id: number
  data_values: { title?: string; image?: string; url?: string }
}

export interface BoughtItem {
  user_name: string
  item: string
  amount: number
  created_at: string
}

export interface Order {
  id: number
  total_amount: number
  created_at: string
  deposit?: { trx: string }
  order_items?: OrderItem[]
  orderItems?: OrderItem[]
}

export interface OrderItem {
  id: number
  product?: Product
  product_detail?: { details: string }
}

export interface DashboardWidget {
  total_payments: number
  total_orders: number
  total_tickets: number
}
