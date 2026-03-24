import { useState } from 'react'
import { Link, Outlet, useLocation, useNavigate } from 'react-router-dom'
import { useAuth } from '../contexts/AuthContext'
import { useTheme } from '../contexts/ThemeContext'

const navItems = [
  {
    to: '/dashboard',
    label: 'Home',
    icon: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={2}
        d="M3 12l2-2m0 0l7-7 7 7m-9 0v9m4-9v9"
      />
    ),
  },
  {
    to: '/products',
    label: 'Categories',
    icon: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={2}
        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
      />
    ),
  },
  {
    to: '/orders',
    label: 'My Orders',
    icon: (
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 10h16M4 14h16M4 18h16" />
    ),
  },
] as const

export default function DashboardLayout() {
  const { user, logout } = useAuth()
  const { theme, toggleTheme } = useTheme()
  const { pathname } = useLocation()
  const navigate = useNavigate()

  const [search, setSearch] = useState('')
  const [mobileOpen, setMobileOpen] = useState(false)

  const balance = (user as { wallet?: { balance: number } })?.wallet?.balance ?? 0
  const initials = user?.username ? user.username.slice(0, 2).toUpperCase() : '?'

  const isDark = theme === 'dark'

  return (
    <div className="dashboard-layout">
      {/* Sidebar overlay for mobile */}
      <div
        className={`sidebar-overlay ${mobileOpen ? 'show' : ''}`}
        onClick={() => setMobileOpen(false)}
        aria-hidden
      />

      {/* Sidebar */}
      <aside className={`dash-sidebar ${mobileOpen ? 'open' : ''}`}>
        <div className="sidebar-logo">
          <Link
            to="/dashboard"
            className=""
            style={{ display: 'flex', alignItems: 'center', textDecoration: 'none' }}
            onClick={() => setMobileOpen(false)}
          >
            <div className="logo-mark">
              <span className="logo-icon">L</span>
              <span>LOGS PLUG</span>
            </div>
          </Link>
        </div>

        <div className="sidebar-balance">
          <div>
            <div className="balance-label">Wallet Balance</div>
            <div className="balance-val">{Number(balance).toLocaleString('en-NG', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</div>
            <div className="balance-currency">NGN</div>
          </div>
          <button
            className="add-funds-mini"
            type="button"
            onClick={() => {
              setMobileOpen(false)
              navigate('/dashboard')
            }}
            aria-label="Add funds"
          >
            +
          </button>
        </div>

        <nav className="sidebar-nav">
          {navItems.map((item) => {
            const active = pathname === item.to
            return (
              <Link
                key={item.to}
                to={item.to}
                className={`dash-nav-item ${active ? 'active' : ''}`}
                onClick={() => setMobileOpen(false)}
              >
                <span className="nav-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                    {item.icon}
                  </svg>
                </span>
                {item.label}
              </Link>
            )
          })}
        </nav>

        <div className="sidebar-bottom">
          <div className="sidebar-theme-wrap">
            <button
              type="button"
              onClick={() => toggleTheme()}
              title={isDark ? 'Switch to light' : 'Switch to dark'}
              aria-label="Toggle theme"
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                justifyContent: 'center',
                width: 36,
                height: 36,
                borderRadius: 10,
                border: '1px solid hsl(220 15% 88%)',
                background: isDark ? 'hsl(220 25% 18%)' : 'hsl(0 0% 100%)',
                color: isDark ? 'hsl(220 15% 95%)' : 'hsl(220 15% 40%)',
                cursor: 'pointer',
              }}
            >
              {isDark ? (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M7.05 7.05l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
              ) : (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                </svg>
              )}
            </button>
          </div>

          <div
            className="user-row"
            onClick={() => {
              // no dedicated profile page in this app; keep as noop for now
              setMobileOpen(false)
            }}
          >
            <div className="user-avatar">{initials}</div>
            <div className="user-info">
              <div className="uname">{user?.username ?? 'User'}</div>
              <div className="uemail">{user?.email ?? ''}</div>
            </div>
          </div>

          <button
            type="button"
            className="signout-btn"
            onClick={() => {
              logout()
              navigate('/')
              setMobileOpen(false)
            }}
          >
            Sign Out
          </button>
        </div>
      </aside>

      {/* Main */}
      <div className="dash-main">
        {/* Topbar */}
        <div className="dash-topbar">
          <button
            type="button"
            className="hamburger-btn"
            onClick={() => setMobileOpen((v) => !v)}
            aria-label="Open menu"
          >
            <span />
            <span />
            <span />
          </button>

          <div className="topbar-title">LOGS PLUG</div>

          <div className="topbar-search">
            <span className="s-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
            <input
              type="text"
              placeholder="Search for products or categories..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>

          <div className="dash-header-right">
            <div className="topbar-bell-wrap">
              <button type="button" className="topbar-bell-btn" aria-label="Notifications">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-6-6 6 6 0 00-6 6v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
              </button>
            </div>

            <div className="topbar-theme-wrap">
              <button
                type="button"
                onClick={() => toggleTheme()}
                aria-label="Toggle theme"
                title={isDark ? 'Switch to light' : 'Switch to dark'}
                style={{
                  width: 36,
                  height: 36,
                  borderRadius: 10,
                  border: '1px solid hsl(220 20% 88%)',
                  background: 'hsl(var(--db-bg))',
                  color: 'hsl(var(--db-text))',
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                }}
              >
                {isDark ? (
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M7.05 7.05l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                  </svg>
                ) : (
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                  </svg>
                )}
              </button>
            </div>

            <div
              className="dash-user-pill"
              role="button"
              tabIndex={0}
              onClick={() => {
                // optional click action
              }}
              aria-label="Wallet balance"
            >
              <span className="bal-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden>
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-9-4h12l-1-1m0 0l-2 2m2-2l-2-2" />
                </svg>
              </span>
              <span className="bal-text">NGN {Number(balance).toLocaleString()}</span>
            </div>

            <div className="topbar-avatar">{initials}</div>
          </div>
        </div>

        {/* Mobile search section (CSS toggles visibility by screen size) */}
        <div className="mobile-search-section">
          <div className="mobile-search-wrap">
            <i aria-hidden>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </i>
            <input
              type="text"
              placeholder="Search for products or categories..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>
        </div>

        {/* Content */}
        <div className="dash-content">
          <Outlet context={{ search, theme }} />
        </div>
      </div>
    </div>
  )
}
