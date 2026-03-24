import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { Button } from '../components/ui/button'
import { Input } from '../components/ui/input'
import { Label } from '../components/ui/label'
import { useAuth } from '../contexts/AuthContext'

export default function Login() {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const { login } = useAuth()
  const navigate = useNavigate()

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')
    setLoading(true)
    try {
      await login(username, password)
      navigate('/products')
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Login failed')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-neutral-950 text-white flex">
      <div className="hidden lg:flex lg:w-1/2 bg-neutral-900 border-r border-neutral-800 flex-col justify-center px-16">
        <Link to="/" className="text-2xl font-bold tracking-tight text-white mb-16">
          LOGS <span className="text-violet-400">PLUG</span>
        </Link>
        <h2 className="text-3xl font-bold tracking-tight mb-4">Welcome back</h2>
        <p className="text-neutral-400 text-lg">
          Sign in to access your account and continue buying.
        </p>
      </div>
      <div className="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 py-12">
        <div className="w-full max-w-sm mx-auto">
          <Link to="/" className="lg:hidden text-xl font-bold text-white mb-8 inline-block">
            LOGS <span className="text-violet-400">PLUG</span>
          </Link>
          <h1 className="text-2xl font-bold mb-2 lg:hidden">Sign in</h1>
          <p className="text-neutral-400 mb-8">Enter your credentials</p>
          <form onSubmit={handleSubmit} className="space-y-5">
            {error && (
              <p className="text-sm text-red-400 bg-red-500/10 border border-red-500/30 p-3 rounded-lg">
                {error}
              </p>
            )}
            <div className="space-y-2">
              <Label htmlFor="username" className="text-neutral-300">Email or username</Label>
              <Input
                id="username"
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                placeholder="you@example.com"
                required
                className="bg-neutral-900 border-neutral-700 text-white placeholder:text-neutral-500 focus-visible:ring-violet-500 focus-visible:border-violet-500"
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="password" className="text-neutral-300">Password</Label>
              <Input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                className="bg-neutral-900 border-neutral-700 text-white placeholder:text-neutral-500 focus-visible:ring-violet-500 focus-visible:border-violet-500"
              />
            </div>
            <Button
              type="submit"
              className="w-full bg-white text-neutral-950 hover:bg-neutral-200 font-semibold py-6 rounded-lg"
              disabled={loading}
            >
              {loading ? 'Signing in…' : 'Sign in'}
            </Button>
          </form>
          <p className="mt-8 text-center text-neutral-400 text-sm">
            New here?{' '}
            <Link to="/register" className="text-violet-400 font-medium hover:underline">
              Create an account
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}
