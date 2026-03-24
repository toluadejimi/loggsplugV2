import { Link } from 'react-router-dom'
import { useAuth } from '../contexts/AuthContext'

const FEATURES = [
  'Instant delivery',
  'Verified accounts',
  'Secure payment',
  '24/7 support',
  'Real engagement',
  'Money-back guarantee',
]

const STEPS = [
  { step: 1, title: 'Browse & select', desc: 'Choose platform and account. See stats and pricing.' },
  { step: 2, title: 'Check out', desc: 'Pay securely. Multiple options.' },
  { step: 3, title: 'Get access', desc: 'Receive credentials instantly. Start using.' },
]

export default function Home() {
  const { user } = useAuth()

  return (
    <div className="min-h-screen bg-neutral-950 text-white">
      {/* Hero */}
      <section className="relative px-6 pt-16 pb-24 sm:pt-24 sm:pb-32 overflow-hidden">
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(120,80,220,0.2),transparent)]" />
        <div className="absolute inset-0 bg-[linear-gradient(to_bottom,transparent_60%,rgba(0,0,0,0.8)_100%)]" />
        <div className="relative z-10 max-w-4xl mx-auto text-center">
          <p className="text-violet-400 text-sm font-medium uppercase tracking-widest mb-4">
            Premium social media accounts
          </p>
          <h1 className="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight mb-6">
            Premium accounts.
            <br />
            <span className="bg-gradient-to-r from-violet-400 via-fuchsia-400 to-violet-400 bg-clip-text text-transparent">
              Instant access.
            </span>
          </h1>
          <p className="text-neutral-400 text-lg sm:text-xl max-w-xl mx-auto mb-10">
            Verified accounts for every major platform. Secure checkout and delivery in minutes.
          </p>
          <Link
            to={user ? '/products' : '/login'}
            className="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-neutral-950 font-semibold hover:bg-neutral-200 transition-colors"
          >
            {user ? 'Browse catalog' : 'Get started'}
          </Link>
        </div>
      </section>

      {/* Stats strip */}
      <section className="border-y border-neutral-800 py-8">
        <div className="max-w-5xl mx-auto px-6 grid grid-cols-3 gap-8 text-center">
          <div>
            <p className="text-2xl sm:text-3xl font-bold text-white">50K+</p>
            <p className="text-neutral-500 text-sm mt-1">Transactions</p>
          </div>
          <div>
            <p className="text-2xl sm:text-3xl font-bold text-white">24h</p>
            <p className="text-neutral-500 text-sm mt-1">Support</p>
          </div>
          <div>
            <p className="text-2xl sm:text-3xl font-bold text-white">100%</p>
            <p className="text-neutral-500 text-sm mt-1">Secure</p>
          </div>
        </div>
      </section>

      {/* Marquee-style features */}
      <section className="py-12 border-b border-neutral-800 overflow-hidden">
        <div className="flex animate-marquee gap-12 whitespace-nowrap">
          {[...FEATURES, ...FEATURES].map((f, i) => (
            <span key={i} className="text-neutral-500 text-sm font-medium uppercase tracking-wider">
              {f}
            </span>
          ))}
        </div>
      </section>

      {/* Who we are */}
      <section className="py-20 px-6">
        <div className="max-w-4xl mx-auto">
          <h2 className="text-2xl sm:text-3xl font-bold mb-4">Verified accounts for every platform.</h2>
          <p className="text-neutral-400 text-lg mb-12">
            LOGS PLUG delivers established accounts with real engagement. Browse by platform, pay securely, get credentials instantly.
          </p>
          <div className="flex flex-wrap gap-3">
            {['Instagram', 'Twitter/X', 'TikTok', 'YouTube', 'Facebook', 'LinkedIn'].map((name) => (
              <span
                key={name}
                className="px-4 py-2 rounded-full border border-neutral-700 text-neutral-400 text-sm hover:border-violet-500/50 hover:text-violet-400 transition-colors"
              >
                {name}
              </span>
            ))}
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-16">
            {[
              { value: '10K+', label: 'Accounts sold' },
              { value: '5★', label: 'Rating' },
              { value: '24/7', label: 'Support' },
              { value: '98%', label: 'Satisfaction' },
            ].map(({ value, label }) => (
              <div key={label}>
                <p className="text-2xl font-bold text-white">{value}</p>
                <p className="text-neutral-500 text-sm mt-1">{label}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Features grid */}
      <section className="py-20 px-6 bg-neutral-900/50">
        <div className="max-w-4xl mx-auto">
          <h2 className="text-2xl sm:text-3xl font-bold mb-2">Built for speed and trust</h2>
          <p className="text-neutral-500 mb-12">What you get with every order.</p>
          <div className="grid sm:grid-cols-2 gap-6">
            {[
              { title: 'Verified accounts', desc: 'Every account is authentic with real followers and engagement. Vetted before listing.' },
              { title: 'Instant delivery', desc: 'Credentials delivered within minutes.' },
              { title: 'Secure payment', desc: 'Encrypted checkout.' },
              { title: '24/7 support', desc: 'Help when you need it.' },
            ].map(({ title, desc }) => (
              <div key={title} className="p-6 rounded-xl border border-neutral-800 bg-neutral-950/50">
                <h3 className="font-semibold text-white mb-2">{title}</h3>
                <p className="text-neutral-500 text-sm">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Process */}
      <section className="py-20 px-6">
        <div className="max-w-4xl mx-auto">
          <h2 className="text-2xl sm:text-3xl font-bold mb-2">Three steps to your account</h2>
          <p className="text-neutral-500 mb-12">Simple and fast.</p>
          <div className="grid sm:grid-cols-3 gap-8">
            {STEPS.map(({ step, title, desc }) => (
              <div key={step} className="relative">
                <span className="inline-flex items-center justify-center w-10 h-10 rounded-full bg-violet-500/20 text-violet-400 font-bold text-lg mb-4">
                  {step}
                </span>
                <h3 className="font-semibold text-white mb-2">{title}</h3>
                <p className="text-neutral-500 text-sm">{desc}</p>
                {step < STEPS.length && (
                  <div className="hidden sm:block absolute top-5 left-14 w-[calc(100%-3rem)] h-px bg-neutral-800" />
                )}
              </div>
            ))}
          </div>
          <div className="mt-12 text-center">
            <Link
              to={user ? '/products' : '/login'}
              className="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-neutral-950 font-semibold hover:bg-neutral-200 transition-colors"
            >
              Get started
            </Link>
          </div>
        </div>
      </section>

      {/* Final CTA */}
      <section className="py-20 px-6 border-t border-neutral-800">
        <div className="max-w-2xl mx-auto text-center">
          <h2 className="text-2xl sm:text-3xl font-bold mb-4">Ready to start?</h2>
          <p className="text-neutral-500 mb-8">Join thousands who use LOGS PLUG for verified accounts.</p>
          <Link
            to={user ? '/products' : '/register'}
            className="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-500 transition-colors"
          >
            {user ? 'Browse catalog' : 'Create account'}
          </Link>
        </div>
      </section>
    </div>
  )
}
