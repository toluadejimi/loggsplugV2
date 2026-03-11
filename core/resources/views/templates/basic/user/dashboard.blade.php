@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="udashboard">
    <div class="udashboard__wrap">
        {{-- Sidebar --}}
        <aside class="udashboard__sidebar">
            <div class="udashboard__sidebar-inner">
                <button type="button" class="udashboard__sidebar-close d-lg-none" aria-label="Close menu"><i class="las la-times"></i></button>
                <div class="udashboard__user">
                    <div class="udashboard__user-avatar">
                        <span>{{ strtoupper(mb_substr($user->username ?? 'U', 0, 1)) }}</span>
                    </div>
                    <h2 class="udashboard__user-name">{{ $user->fullname ?? $user->username }}</h2>
                    <p class="udashboard__user-email">{{ $user->email }}</p>
                    @if(@$user->address && (@$user->address->address || @$user->address->city))
                        <p class="udashboard__user-loc">
                            {{ @$user->address->address }}
                            @if(@$user->address->city || @$user->address->state || @$user->address->zip)
                                <br>{{ implode(', ', array_filter([@$user->address->city, @$user->address->state, @$user->address->zip])) }}
                            @endif
                            @if(@$user->address->country) <br>{{ @$user->address->country }} @endif
                        </p>
                    @endif
                    <a href="{{ route('user.profile.setting') }}" class="udashboard__user-edit"><i class="las la-pencil-alt"></i> @lang('Edit profile')</a>
                </div>
                <nav class="udashboard__nav">
                    <span class="udashboard__nav-title">@lang('Shortcuts')</span>
                    <a href="{{ route('user.reseller.index') }}" class="udashboard__nav-link"><i class="las la-store"></i> @lang('Reseller')</a>
                    <a href="{{ route('products') }}" class="udashboard__nav-link"><i class="las la-shopping-bag"></i> @lang('Products')</a>
                    <a href="{{ route('ticket.open') }}" class="udashboard__nav-link"><i class="las la-plus-circle"></i> @lang('New Ticket')</a>
                    <a href="{{ route('user.logout') }}" class="udashboard__nav-link udashboard__nav-link--danger"><i class="las la-sign-out-alt"></i> @lang('Logout')</a>
                </nav>
            </div>
        </aside>

        <button type="button" class="udashboard__menu-btn d-lg-none" aria-label="Open menu"><i class="las la-bars"></i></button>

        {{-- Main content --}}
        <main class="udashboard__main">
            <div class="udashboard__welcome">
                <h1 class="udashboard__welcome-title">@lang('Welcome back'), {{ $user->firstname ?? $user->username }}.</h1>
                <p class="udashboard__welcome-sub">@lang('Here’s an overview of your account.')</p>
            </div>

            {{-- Stat cards --}}
            <div class="udashboard__stats">
                <a href="{{ route('user.deposit.new') }}" class="udashboard__stat udashboard__stat--wallet">
                    <div class="udashboard__stat-row">
                        <div class="udashboard__stat-icon"><i class="las la-wallet"></i></div>
                        <div class="udashboard__stat-body">
                            <span class="udashboard__stat-label">@lang('My Wallet')</span>
                            <span class="udashboard__stat-value">{{ $general->cur_sym }}{{ number_format(Auth::user()->balance, 2) }}</span>
                        </div>
                    </div>
                    <span class="udashboard__stat-action">@lang('Fund Wallet') <i class="las la-arrow-right"></i></span>
                </a>
                <a href="{{ route('user.deposit.history') }}" class="udashboard__stat udashboard__stat--deposits">
                    <div class="udashboard__stat-row">
                        <div class="udashboard__stat-icon"><i class="las la-hand-holding-usd"></i></div>
                        <div class="udashboard__stat-body">
                            <span class="udashboard__stat-label">@lang('Total Deposits')</span>
                            <span class="udashboard__stat-value">{{ $general->cur_sym }}{{ number_format((float) (@$widget['total_payments'] ?? 0), 2) }}</span>
                        </div>
                    </div>
                    <span class="udashboard__stat-action">@lang('Deposit history') <i class="las la-arrow-right"></i></span>
                </a>
                <a href="{{ route('user.orders') }}" class="udashboard__stat udashboard__stat--orders">
                    <div class="udashboard__stat-row">
                        <div class="udashboard__stat-icon"><i class="las la-shopping-cart"></i></div>
                        <div class="udashboard__stat-body">
                            <span class="udashboard__stat-label">@lang('Orders')</span>
                            <span class="udashboard__stat-value">{{ getAmount(@$widget['total_orders']) }}</span>
                        </div>
                    </div>
                    <span class="udashboard__stat-action">@lang('View orders') <i class="las la-arrow-right"></i></span>
                </a>
            </div>

            {{-- Latest payments --}}
            <section class="udashboard__section">
                <div class="udashboard__section-head">
                    <h2 class="udashboard__section-title">@lang('Latest Payments')</h2>
                    <a href="{{ route('user.deposit.history') }}" class="udashboard__section-link">@lang('View all') <i class="las la-arrow-right"></i></a>
                </div>
                <div class="udashboard__table-wrap">
                    <table class="udashboard__table">
                        <thead>
                            <tr>
                                <th>@lang('Trx')</th>
                                <th>@lang('Time')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestDeposits as $deposit)
                            <tr>
                                <td><code class="udashboard__trx">{{ $deposit->trx }}</code></td>
                                <td><span class="udashboard__muted">{{ diffForHumans($deposit->created_at) }}</span></td>
                                <td><strong>{{ $general->cur_sym }}{{ showAmount($deposit->amount) }}</strong></td>
                                <td>@php echo $deposit->statusBadge; @endphp</td>
                                <td>
                                    @if($deposit->status == 0)
                                        <a href="/user/resolve-deposit?trx={{ $deposit->trx }}" class="udashboard__btn-resolve">@lang('Resolve')</a>
                                    @else
                                        <span class="udashboard__muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="udashboard__empty">{{ __($emptyMessage ?? 'No payments yet') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

{{-- Telegram float --}}
<a href="https://t.me/loggsplugca" class="udashboard__telegram" target="_blank" rel="noopener" aria-label="Contact on Telegram">
    <i class="lab la-telegram"></i>
</a>

{{-- Detail modal --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content udashboard__modal">
            <div class="modal-header">
                <h6 class="modal-title" id="detailModalTitle">@lang('Details')</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush userData"></ul>
                <div class="feedback"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* ========== Dashboard layout ========== */
.udashboard {
    min-height: 70vh;
    padding: 1.5rem 0 4rem;
}
.udashboard__wrap {
    display: flex;
    gap: 1.5rem;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1rem;
}
@media (min-width: 992px) {
    .udashboard__wrap { padding: 0 1.5rem; }
}

/* ========== Sidebar ========== */
.udashboard__sidebar {
    width: 280px;
    flex-shrink: 0;
}
.udashboard__sidebar-inner {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
    border: 1px solid rgba(0,0,0,.04);
    overflow: hidden;
    position: relative;
}
.udashboard__sidebar-close {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border: none;
    background: #f1f5f9;
    border-radius: 8px;
    color: #475569;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.udashboard__user {
    padding: 1.75rem 1.25rem;
    text-align: center;
    border-bottom: 1px solid #f1f5f9;
}
.udashboard__user-avatar {
    width: 64px;
    height: 64px;
    margin: 0 auto 12px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 700;
}
.udashboard__user-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 4px;
    line-height: 1.3;
}
.udashboard__user-email {
    font-size: 0.8125rem;
    color: #64748b;
    margin: 0 0 8px;
}
.udashboard__user-loc {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0 0 10px;
    line-height: 1.4;
}
.udashboard__user-edit {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6366f1;
    text-decoration: none;
}
.udashboard__user-edit:hover { color: #4f46e5; }

.udashboard__nav {
    padding: 1rem 1.25rem;
}
.udashboard__nav-title {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94a3b8;
    margin-bottom: 10px;
    padding: 0 4px;
}
.udashboard__nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #334155;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: background .2s, color .2s;
}
.udashboard__nav-link i { font-size: 1.2rem; opacity: .85; }
.udashboard__nav-link:hover {
    background: #f8fafc;
    color: #6366f1;
}
.udashboard__nav-link--danger:hover { color: #dc2626; }

@media (max-width: 991.98px) {
    .udashboard__sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 300px;
        max-width: 85vw;
        height: 100vh;
        z-index: 1050;
        transform: translateX(-100%);
        transition: transform .25s ease;
        overflow-y: auto;
    }
    .udashboard__sidebar.show { transform: translateX(0); }
    .udashboard__sidebar-inner { min-height: 100%; border-radius: 0; }
    .udashboard__menu-btn {
        position: fixed;
        bottom: 1.25rem;
        right: 1.25rem;
        width: 56px;
        height: 56px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, .4);
        z-index: 1040;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

/* ========== Main ========== */
.udashboard__main {
    flex: 1;
    min-width: 0;
}

.udashboard__welcome {
    margin-bottom: 1.75rem;
}
.udashboard__welcome-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 6px;
    letter-spacing: -0.02em;
}
@media (min-width: 768px) {
    .udashboard__welcome-title { font-size: 2rem; }
}
.udashboard__welcome-sub {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0;
}

/* ========== Stat cards ========== */
.udashboard__stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}
@media (min-width: 576px) {
    .udashboard__stats { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 992px) {
    .udashboard__stats { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
}

.udashboard__stat {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 0;
    padding: 1.25rem 1.5rem;
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.04);
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
}
.udashboard__stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(0,0,0,.08);
}
.udashboard__stat-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
    min-width: 0;
}
.udashboard__stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    flex-shrink: 0;
}
.udashboard__stat--wallet .udashboard__stat-icon { background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); }
.udashboard__stat--deposits .udashboard__stat-icon { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
.udashboard__stat--orders .udashboard__stat-icon { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); }
.udashboard__stat-body {
    flex: 1;
    min-width: 0;
}
.udashboard__stat-label {
    display: block;
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 4px;
}
.udashboard__stat-value {
    display: block;
    font-size: 1.35rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.udashboard__stat-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #6366f1;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}
.udashboard__stat-action:hover { color: #4f46e5; }

/* ========== Section & table ========== */
.udashboard__section {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.04);
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
    overflow: hidden;
}
.udashboard__section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
}
.udashboard__section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
}
.udashboard__section-link {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6366f1;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.udashboard__section-link:hover { color: #4f46e5; }

.udashboard__table-wrap {
    overflow-x: auto;
}
.udashboard__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
}
.udashboard__table th {
    text-align: left;
    padding: 12px 1rem;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.udashboard__table td {
    padding: 14px 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.udashboard__table tbody tr:hover {
    background: #fafafa;
}
.udashboard__table tbody tr:last-child td { border-bottom: none; }
.udashboard__trx {
    font-size: 0.8125rem;
    background: #f1f5f9;
    padding: 4px 8px;
    border-radius: 6px;
    color: #475569;
}
.udashboard__muted { color: #94a3b8; }

/* Latest Payments status badges – ensure visible */
.udashboard__table td .badge {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.35rem 0.7rem;
    border-radius: 9999px;
    color: #fff !important;
}
.udashboard__table td .badge--warning,
.udashboard__table td .badge--dark {
    background: #64748b !important;
    color: #fff !important;
}
.udashboard__table td .badge--success {
    background: #15803d !important;
    color: #fff !important;
}
.udashboard__table td .badge--danger {
    background: #b91c1c !important;
    color: #fff !important;
}

.udashboard__btn-resolve {
    display: inline-block;
    padding: 6px 12px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #dc2626;
    background: rgba(220, 38, 38, .08);
    border-radius: 8px;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.udashboard__btn-resolve:hover {
    background: rgba(220, 38, 38, .15);
    color: #b91c1c;
}
.udashboard__empty {
    text-align: center;
    padding: 2.5rem 1rem !important;
    color: #94a3b8;
    font-size: 0.9375rem;
}

@media (max-width: 767.98px) {
    .udashboard__table th,
    .udashboard__table td { padding: 10px 0.75rem; font-size: 0.875rem; }
}

/* ========== Telegram float ========== */
.udashboard__telegram {
    position: fixed;
    bottom: 1.25rem;
    right: 1.25rem;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0088cc 0%, #229ED9 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    box-shadow: 0 6px 20px rgba(0, 136, 204, .45);
    z-index: 1030;
    transition: transform .2s, box-shadow .2s;
}
.udashboard__telegram:hover {
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0 8px 28px rgba(0, 136, 204, .5);
}
@media (min-width: 992px) {
    .udashboard__telegram { bottom: 2rem; right: 2rem; }
}
@media (max-width: 991.98px) {
    .udashboard__telegram { right: 1rem; bottom: 5rem; }
}

/* Modal polish */
.udashboard__modal { border-radius: 16px; border: none; box-shadow: 0 24px 48px rgba(0,0,0,.12); }
.udashboard__modal .modal-header { border-bottom: 1px solid #f1f5f9; }
.udashboard__modal .modal-footer { border-top: 1px solid #f1f5f9; }
</style>
@endpush

@push('script')
<script>
(function($) {
    "use strict";
    $('.detailBtn').on('click', function() {
        var modal = $('#detailModal');
        var userData = $(this).data('info');
        var html = '';
        if (userData) {
            userData.forEach(function(element) {
                if (element.type != 'file') {
                    html += '<li class="list-group-item d-flex justify-content-between align-items-center"><span>' + element.name + '</span><span>' + element.value + '</span></li>';
                }
            });
        }
        var adminFeedback = $(this).data('admin_feedback') != undefined
            ? '<div class="my-3 ms-2"><strong>{{ __("Admin Feedback") }}</strong><p>' + $(this).data('admin_feedback') + '</p></div>'
            : '';
        if (!html && !adminFeedback) html = '<span class="d-block text-center mt-2 mb-2">{{ __($emptyMessage ?? "Data not found") }}</span>';
        modal.find('.userData').html(html);
        modal.find('.feedback').html(adminFeedback);
        modal.modal('show');
    });
    $('.udashboard__menu-btn').on('click', function() {
        $('.udashboard__sidebar').addClass('show');
        if (!$('.sidebar-overlay').length) $('body').append('<div class="sidebar-overlay"></div>');
        $('.sidebar-overlay').addClass('show');
    });
    $('.udashboard__sidebar-close, .sidebar-overlay').on('click', function() {
        $('.udashboard__sidebar').removeClass('show');
        $('.sidebar-overlay').removeClass('show');
    });
})(jQuery);
</script>
@endpush
