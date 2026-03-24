import { createContext, useCallback, useContext, useEffect, useState } from 'react'
import { apiGet, apiPost, clearToken, setToken } from '../api/client'
import type { User } from '../types/api'

interface AuthState {
  user: User | null
  loading: boolean
  login: (username: string, password: string) => Promise<void>
  register: (data: { username: string; email: string; password: string; password_confirmation: string; agree?: boolean }) => Promise<void>
  logout: () => Promise<void>
  refreshUser: () => Promise<void>
}

const AuthContext = createContext<AuthState | null>(null)

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [loading, setLoading] = useState(true)

  const refreshUser = useCallback(async () => {
    const t = localStorage.getItem('token')
    if (!t) {
      setUser(null)
      setLoading(false)
      return
    }
    try {
      const data = await apiGet<{ user: User }>('/api/user')
      setUser(data.user)
    } catch {
      clearToken()
      setUser(null)
    } finally {
      setLoading(false)
    }
  }, [])

  useEffect(() => {
    refreshUser()
  }, [refreshUser])

  const login = useCallback(async (username: string, password: string) => {
    const data = await apiPost<{ user: User; token: string }>('/api/login', { username, password })
    setToken(data.token)
    setUser(data.user)
  }, [])

  const register = useCallback(
    async (body: { username: string; email: string; password: string; password_confirmation: string; agree?: boolean }) => {
      const data = await apiPost<{ user: User; token: string }>('/api/register', { ...body, agree: body.agree ?? true })
      setToken(data.token)
      setUser(data.user)
    },
    []
  )

  const logout = useCallback(async () => {
    try {
      await apiPost('/api/logout', {})
    } finally {
      clearToken()
      setUser(null)
    }
  }, [])

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, refreshUser }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth must be used within AuthProvider')
  return ctx
}
