@php
    $loginNotice = \App\Models\Frontend::firstOrCreate(
        ['data_keys' => 'login_notice.data'],
        [
            'data_values' => [
                'status' => \App\Constants\Status::ENABLE,
                'title' => 'Notice',
                'description' => '<p>Welcome! Update this message in Admin → Login Notice.</p>',
            ],
        ]
    );

    $values = $loginNotice->data_values;
    $status = is_object($values) ? ($values->status ?? 0) : 0;
    $title = is_object($values) ? ($values->title ?? 'Notice') : 'Notice';
    $description = is_object($values) ? trim((string) ($values->description ?? '')) : '';
    $noticeEnabled = in_array((string) $status, ['1', 'true'], true) || $status === true || (int) $status === \App\Constants\Status::ENABLE;
    $noticeEnabled = $noticeEnabled && $description !== '';

    $path = trim(request()->path(), '/');
    $onProductsPage = request()->routeIs('products')
        || $path === 'products'
        || str_starts_with($path, 'products/');

    $showAfterLogin = (bool) session()->pull('show_login_notice', false);
    $showLoginNotice = $noticeEnabled && ($onProductsPage || $showAfterLogin);
@endphp

@if ($showLoginNotice)
<div id="loginNoticeOverlay" class="login-notice-overlay" role="dialog" aria-modal="true" aria-labelledby="loginNoticeTitle">
    <div class="login-notice-card">
        <div class="login-notice-card__header">
            <h5 class="login-notice-card__title" id="loginNoticeTitle">
                <i class="las la-bell"></i>
                <span>{{ $title ?: __('Notice') }}</span>
            </h5>
        </div>
        <div class="login-notice-card__body">
            @php echo $description @endphp
        </div>
        <div class="login-notice-card__footer">
            <button type="button" class="login-notice-card__close" id="loginNoticeCloseBtn">@lang('Close')</button>
        </div>
    </div>
</div>

<style>
.login-notice-overlay {
    position: fixed !important;
    inset: 0 !important;
    z-index: 2147483000 !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.65) !important;
}
.login-notice-overlay.is-hidden {
    display: none !important;
}
.login-notice-card {
    width: 100%;
    max-width: 420px;
    max-height: 85vh;
    overflow: auto;
    background: #1a1a1a;
    color: #f5f5f5;
    border: 1px solid #333;
    border-radius: 10px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
}
.login-notice-card__header {
    border-bottom: 1px solid #333;
    padding: 1rem 1.25rem;
}
.login-notice-card__title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
}
.login-notice-card__title i {
    font-size: 1.25rem;
}
.login-notice-card__body {
    padding: 1.25rem;
    font-size: 0.95rem;
    line-height: 1.6;
    color: #eee;
}
.login-notice-card__body p {
    margin-bottom: 0.85rem;
}
.login-notice-card__body a {
    color: #4da3ff !important;
    word-break: break-all;
}
.login-notice-card__footer {
    padding: 0 1.25rem 1.25rem;
}
.login-notice-card__close {
    width: 100%;
    background: #b91c1c;
    color: #fff;
    border: 0;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-weight: 600;
    cursor: pointer;
}
.login-notice-card__close:hover,
.login-notice-card__close:focus {
    background: #991b1b;
    color: #fff;
}
body.login-notice-open {
    overflow: hidden !important;
}
</style>

<script>
(function () {
    var overlay = document.getElementById('loginNoticeOverlay');
    var closeBtn = document.getElementById('loginNoticeCloseBtn');
    if (!overlay) return;

    document.body.classList.add('login-notice-open');

    function closeNotice() {
        overlay.classList.add('is-hidden');
        document.body.classList.remove('login-notice-open');
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeNotice();
        });
    }
})();
</script>
@endif
