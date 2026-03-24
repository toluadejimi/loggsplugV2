import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { Button } from '../components/ui/button'
import { Input } from '../components/ui/input'
import { Label } from '../components/ui/label'
import { useAuth } from '../contexts/AuthContext'

export default function Register() {
  const [username, setUsername] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')
  const [agree, setAgree] = useState(false)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const { register } = useAuth()
  const navigate = useNavigate()

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')
    if (password !== passwordConfirmation) {
      setError('Passwords do not match')
      return
    }
    if (!agree) {
      setError('You must agree to the terms to register')
      return
    }
    setLoading(true)
    try {
      await register({
        username,
        email,
        password,
        password_confirmation: passwordConfirmation,
        agree: true,
      })
      navigate('/products')
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Registration failed')
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
        <h2 className="text-3xl font-bold tracking-tight mb-4">Create an account</h2>
        <p className="text-neutral-400 text-lg">
          Join to browse products and buy verified accounts in minutes.
        </p>
      </div>
      <div className="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 py-12">
        <div className="w-full max-w-sm mx-auto">
          <Link to="/" className="lg:hidden text-xl font-bold text-white mb-8 inline-block">
            LOGS <span className="text-violet-400">PLUG</span>
          </Link>
          <h1 className="text-2xl font-bold mb-2 lg:hidden">Register</h1>
          <p className="text-neutral-400 mb-8">Fill in your details</p>
          <form onSubmit={handleSubmit} className="space-y-5">
            {error && (
              <p className="text-sm text-red-400 bg-red-500/10 border border-red-500/30 p-3 rounded-lg">
                {error}
              </p>
            )}
            <div className="space-y-2">
              <Label htmlFor="username" className="text-neutral-300">Username</Label>
              <Input
                id="username"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                minLength={4}
                required
                className="bg-neutral-900 border-neutral-700 text-white placeholder:text-neutral-500 focus-visible:ring-violet-500 focus-visible:border-violet-500"
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="email" className="text-neutral-300">Email</Label>
              <Input
                id="email"
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
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
            <div className="space-y-2">
              <Label htmlFor="password_confirmation" className="text-neutral-300">Confirm password</Label>
              <Input
                id="password_confirmation"
                type="password"
                value={passwordConfirmation}
                onChange={(e) => setPasswordConfirmation(e.target.value)}
                required
                className="bg-neutral-900 border-neutral-700 text-white placeholder:text-neutral-500 focus-visible:ring-violet-500 focus-visible:border-violet-500"
              />
            </div>
            <div className="flex items-start gap-3">
              <input
                type="checkbox"
                id="agree"
                checked={agree}
                onChange={(e) => setAgree(e.target.checked)}
                className="mt-1 h-4 w-4 rounded border-neutral-600 bg-neutral-900 text-violet-500 focus:ring-violet-500"
              />
              <Label htmlFor="agree" className="text-neutral-400 text-sm cursor-pointer leading-tight">
                I agree to the terms of service and privacy policy
              </Label>
            </div>
            <Button
              type="submit"
              className="w-full bg-white text-neutral-950 hover:bg-neutral-200 font-semibold py-6 rounded-lg"
              disabled={loading}
            >
              {loading ? 'Creating account…' : 'Create account'}
            </Button>
          </form>
          <p className="mt-8 text-center text-neutral-400 text-sm">
            Already have an account?{' '}
            <Link to="/login" className="text-violet-400 font-medium hover:underline">
              Sign in
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}
