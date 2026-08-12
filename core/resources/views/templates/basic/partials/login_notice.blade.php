@php
    $showLoginNotice = session()->pull('show_login_notice', false);
    $loginNotice = null;
    if ($showLoginNotice) {
        $loginNotice = \App\Models\Frontend::where('data_keys', 'login_notice.data')->first();
    }
@endphp

@if ($showLoginNotice && @$loginNotice->data_values->status == \App\Constants\Status::ENABLE && !empty(@$loginNotice->data_values->description))
<div class="modal fade" id="loginNoticeModal" tabindex="-1" aria-labelledby="loginNoticeTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content login-notice-modal">
            <div class="modal-header login-notice-modal__header">
                <h5 class="modal-title login-notice-modal__title" id="loginNoticeTitle">
                    <i class="las la-bell"></i>
                    <span>{{ @$loginNotice->data_values->title ?: __('Notice') }}</span>
                </h5>
            </div>
            <div class="modal-body login-notice-modal__body">
                @php echo @$loginNotice->data_values->description @endphp
            </div>
            <div class="modal-footer login-notice-modal__footer">
                <button type="button" class="btn login-notice-modal__close" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<style>
.login-notice-modal {
    background: #1a1a1a;
    color: #f5f5f5;
    border: 1px solid #333;
    border-radius: 10px;
    overflow: hidden;
}
.login-notice-modal__header {
    border-bottom: 1px solid #333;
    padding: 1rem 1.25rem;
}
.login-notice-modal__title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
}
.login-notice-modal__title i {
    font-size: 1.25rem;
}
.login-notice-modal__body {
    padding: 1.25rem;
    font-size: 0.95rem;
    line-height: 1.6;
    color: #eee;
}
.login-notice-modal__body p {
    margin-bottom: 0.85rem;
}
.login-notice-modal__body a {
    color: #4da3ff;
    word-break: break-all;
}
.login-notice-modal__footer {
    border-top: 0;
    padding: 0 1.25rem 1.25rem;
}
.login-notice-modal__close {
    width: 100%;
    background: #b91c1c;
    color: #fff;
    border: 0;
    border-radius: 6px;
    padding: 0.7rem 1rem;
    font-weight: 600;
}
.login-notice-modal__close:hover,
.login-notice-modal__close:focus {
    background: #991b1b;
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('loginNoticeModal');
    if (!el) return;
    if (window.bootstrap && bootstrap.Modal) {
        new bootstrap.Modal(el).show();
        return;
    }
    if (window.jQuery && typeof jQuery(el).modal === 'function') {
        jQuery(el).modal('show');
    }
});
</script>
@endif
