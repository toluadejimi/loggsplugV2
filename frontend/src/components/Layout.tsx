import { useState } from 'react'
import { Link, Outlet, useNavigate } from 'react-router-dom'
import { Button } from './ui/button'
import { useAuth } from '../contexts/AuthContext'

export default function Layout() {
  const { user, logout, loading } = useAuth()
  const [drawerOpen, setDrawerOpen] = useState(false)
  const navigate = useNavigate()

  const closeDrawer = () => setDrawerOpen(false)

  const navLink = (to: string, label: string) => (
    <Link
      to={to}
      onClick={closeDrawer}
      className="block py-3 px-4 text-neutral-300 hover:text-white hover:bg-neutral-800/50 rounded-lg transition-colors"
    >
      {label}
    </Link>
  )

  return (
    <div className="min-h-screen flex flex-col bg-neutral-950 text-white">
      <header className="sticky top-0 z-40 border-b border-neutral-800 bg-neutral-950/95 backdrop-blur">
        <div className="container max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
          <button
            type="button"
            onClick={() => setDrawerOpen(true)}
            className="p-2 -ml-2 rounded-lg text-neutral-400 hover:text-white hover:bg-neutral-800 transition-colors md:hidden"
            aria-label="Open menu"
          >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <Link to="/" className="font-bold text-lg tracking-tight">
            LOGS <span className="text-violet-400">PLUG</span>
          </Link>
          <nav className="hidden md:flex items-center gap-1">
            {user && (
              <>
                <Link to="/products" className="px-3 py-2 text-sm text-neutral-400 hover:text-white rounded-lg transition-colors">
                  Products
                </Link>
                <Link to="/dashboard" className="px-3 py-2 text-sm text-neutral-400 hover:text-white rounded-lg transition-colors">
                  Dashboard
                </Link>
                <Link to="/orders" className="px-3 py-2 text-sm text-neutral-400 hover:text-white rounded-lg transition-colors">
                  Orders
                </Link>
              </>
            )}
            {!loading && (
              !user ? (
                <>
                  <Button variant="ghost" size="sm" asChild>
                    <Link to="/login" className="text-neutral-300 hover:text-white">Sign in</Link>
                  </Button>
                  <Button size="sm" asChild className="bg-white text-neutral-950 hover:bg-neutral-200">
                    <Link to="/register">Get started</Link>
                  </Button>
                </>
              ) : (
                <Button variant="ghost" size="sm" onClick={() => { logout(); closeDrawer(); }}>
                  Logout
                </Button>
              )
            )}
          </nav>
        </div>
      </header>

      {/* Side drawer overlay */}
      {drawerOpen && (
        <div
          className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm md:hidden"
          onClick={closeDrawer}
          aria-hidden
        />
      )}

      {/* Side drawer panel */}
      <aside
        className={`fixed top-0 left-0 z-50 h-full w-72 max-w-[85vw] bg-neutral-900 border-r border-neutral-800 shadow-xl transform transition-transform duration-200 ease-out md:hidden ${
          drawerOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <div className="flex items-center justify-between h-14 px-4 border-b border-neutral-800">
          <span className="font-bold">LOGS <span className="text-violet-400">PLUG</span></span>
          <button
            type="button"
            onClick={closeDrawer}
            className="p-2 rounded-lg text-neutral-400 hover:text-white hover:bg-neutral-800 transition-colors"
            aria-label="Close menu"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <nav className="p-4 space-y-1">
          {navLink('/', 'Home')}
          {user && (
            <>
              {navLink('/products', 'Products')}
              {navLink('/dashboard', 'Dashboard')}
              {navLink('/orders', 'Orders')}
            </>
          )}
          {!loading && (
            !user ? (
              <>
                {navLink('/login', 'Sign in')}
                <Link to="/register" onClick={closeDrawer}>
                  <Button className="w-full mt-4 bg-white text-neutral-950 hover:bg-neutral-200">
                    Get started
                  </Button>
                </Link>
              </>
            ) : (
              <button
                type="button"
                onClick={() => { logout(); closeDrawer(); navigate('/'); }}
                className="w-full text-left py-3 px-4 text-neutral-400 hover:text-white hover:bg-neutral-800/50 rounded-lg transition-colors"
              >
                Logout
              </button>
            )
          )}
        </nav>
      </aside>

      <main className="flex-1">
        <Outlet />
      </main>
      <footer className="border-t border-neutral-800 py-6 text-center text-neutral-500 text-sm">
        LOGS PLUG — Premium accounts · Instant delivery
      </footer>
    </div>
  )
}
