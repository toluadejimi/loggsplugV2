<header class="site-header" id="header">
    <div class="site-header__bar">
        <div class="container">
            <nav class="site-header__nav navbar navbar-expand-lg">
                <a class="site-header__logo navbar-brand" href="{{ route('home') }}">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/dark_logo.png') }}" alt="{{ $general->site_name ?? 'Logo' }}">
                </a>
                <button class="site-header__toggler navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteHeaderMenu" aria-controls="siteHeaderMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="site-header__toggler-icon"><i class="las la-bars"></i></span>
                </button>
                <div class="collapse navbar-collapse site-header__collapse" id="siteHeaderMenu">
                    <ul class="navbar-nav site-header__menu ms-lg-auto align-items-lg-center">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('home') }}">@lang('Buy Account')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('user.profile.setting') }}">@lang('Profile')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('user.reseller.index') }}">@lang('Reseller')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('user.change.password') }}">@lang('Change Password')</a>
                            </li>
                            @if($general->multi_language ?? false)
                                @php $language = App\Models\Language::all(); @endphp
                                <li class="nav-item site-header__lang-wrap">
                                    <select class="form-select form-select-sm langSel site-header__lang" aria-label="Language">
                                        @foreach($language as $item)
                                            <option value="{{ $item->code }}" @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </li>
                            @endif
                            <li class="nav-item site-header__action">
                                <a href="{{ route('user.logout') }}" class="site-header__btn site-header__btn--outline">
                                    <i class="las la-sign-out-alt"></i> @lang('Logout')
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link site-header__link" href="{{ route('products') }}">@lang('Products')</a>
                            </li>
                            @if($general->multi_language ?? false)
                                @php $language = App\Models\Language::all(); @endphp
                                <li class="nav-item site-header__lang-wrap">
                                    <select class="form-select form-select-sm langSel site-header__lang" aria-label="Language">
                                        @foreach($language as $item)
                                            <option value="{{ $item->code }}" @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </li>
                            @endif
                            <li class="nav-item site-header__action">
                                <a href="{{ route('user.login') }}" class="site-header__btn site-header__btn--ghost">
                                    <i class="las la-sign-in-alt"></i> @lang('Login')
                                </a>
                                <a href="{{ route('user.register') }}" class="site-header__btn site-header__btn--primary">
                                    <i class="las la-user-plus"></i> @lang('Register')
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>

@push('style')
<style>
/* ---- Site header: polished, modern ---- */
.site-header {
    position: sticky;
    top: 0;
    z-index: 1030;
    background: #fff;
    box-shadow: 0 1px 0 rgba(0,0,0,.06);
}

.site-header__bar {
    padding: 0.5rem 0;
}
@media (min-width: 992px) {
    .site-header__bar {
        padding: 0.65rem 0;
    }
}

.site-header__nav {
    padding: 0;
    gap: 0.75rem;
}

.site-header__logo {
    margin: 0;
    padding: 0.25rem 0;
}
.site-header__logo img {
    height: 36px;
    width: auto;
    display: block;
    object-fit: contain;
}
@media (min-width: 992px) {
    .site-header__logo img {
        height: 40px;
    }
}

.site-header__toggler {
    padding: 0.5rem 0.65rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    color: #374151;
}
.site-header__toggler:focus {
    box-shadow: 0 0 0 3px rgba(55, 65, 81, 0.12);
}
.site-header__toggler-icon {
    font-size: 1.35rem;
    line-height: 1;
}

.site-header__menu {
    gap: 0.125rem;
}
@media (min-width: 992px) {
    .site-header__menu {
        gap: 0.25rem;
    }
}

.site-header__link {
    font-size: 0.9375rem;
    font-weight: 500;
    color: #374151 !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 8px;
    transition: color 0.2s ease, background 0.2s ease;
}
.site-header__link:hover {
    color: #111827 !important;
    background: #f9fafb;
}
@media (max-width: 991.98px) {
    .site-header__link {
        padding: 0.65rem 0.85rem !important;
        border-radius: 6px;
    }
    .site-header__menu .nav-item {
        border-bottom: 1px solid #f3f4f6;
    }
    .site-header__menu .nav-item:last-child {
        border-bottom: none;
    }
}

.site-header__lang-wrap {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
.site-header__lang {
    font-size: 0.8125rem;
    padding: 0.35rem 1.75rem 0.35rem 0.65rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    color: #374151;
    min-width: 100px;
}
@media (max-width: 991.98px) {
    .site-header__lang-wrap {
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid #f3f4f6;
    }
}

.site-header__action {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-left: 0.5rem;
}
@media (min-width: 992px) {
    .site-header__action {
        padding-left: 0.75rem;
        margin-left: 0.25rem;
        border-left: 1px solid #e5e7eb;
    }
}
@media (max-width: 991.98px) {
    .site-header__action {
        padding: 0.85rem;
        border-bottom: 1px solid #f3f4f6;
        flex-wrap: wrap;
    }
}

.site-header__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none !important;
    transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    white-space: nowrap;
}
.site-header__btn i {
    font-size: 1rem;
}

.site-header__btn--ghost {
    color: #374151;
    background: transparent;
}
.site-header__btn--ghost:hover {
    color: #111827;
    background: #f3f4f6;
}

.site-header__btn--outline {
    color: #4b5563;
    border: 1px solid #d1d5db;
    background: #fff;
}
.site-header__btn--outline:hover {
    color: #111827;
    border-color: #9ca3af;
    background: #f9fafb;
}

.site-header__btn--primary {
    color: #fff;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    border: none;
    box-shadow: 0 1px 2px rgba(79, 70, 229, 0.25);
}
.site-header__btn--primary:hover {
    color: #fff;
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
}

.site-header__collapse {
    margin-top: 0.5rem;
}
@media (min-width: 992px) {
    .site-header__collapse {
        margin-top: 0;
    }
}
</style>
@endpush
