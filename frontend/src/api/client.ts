const API_BASE = import.meta.env.VITE_API_URL ?? '' // set in .env, e.g. http://localhost:9090

function getToken(): string | null {
  return localStorage.getItem('token')
}

export async function api<T>(
  path: string,
  options: RequestInit = {}
): Promise<T> {
  const url = path.startsWith('http') ? path : `${API_BASE}${path}`
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    ...(options.headers as object),
  }
  const token = getToken()
  if (token) {
    (headers as Record<string, string>)['Authorization'] = `Bearer ${token}`
  }
  const res = await fetch(url, { ...options, headers, credentials: 'include' })
  const data = await res.json().catch(() => ({}))
  if (!res.ok) {
    throw new Error((data as { message?: string }).message || res.statusText || 'Request failed')
  }
  return data as T
}

export const apiGet = <T>(path: string) => api<T>(path, { method: 'GET' })
export const apiPost = <T>(path: string, body: unknown) =>
  api<T>(path, { method: 'POST', body: JSON.stringify(body) })

export function setToken(token: string) {
  localStorage.setItem('token', token)
}
export function clearToken() {
  localStorage.removeItem('token')
}
