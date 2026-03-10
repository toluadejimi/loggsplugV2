@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="pwd">
    <div class="pwd__container">
        <div class="pwd__head">
            <div>
                <h1 class="pwd__title">@lang('Change Password')</h1>
                <p class="pwd__sub">@lang('Choose a strong password to keep your account secure.')</p>
            </div>
            <a href="{{ route('user.profile.setting') }}" class="pwd__back"><i class="las la-arrow-left"></i> @lang('Profile')</a>
        </div>

        @if(session('notify'))
            @foreach(session('notify') as $n)
                <div class="pwd__alert pwd__alert--{{ $n[0] === 'error' ? 'danger' : 'success' }}" role="alert">
                    {{ __($n[1]) }}
                </div>
            @endforeach
        @endif
        @if(session('message'))
            <div class="pwd__alert pwd__alert--success">{{ session('message') }}</div>
        @endif
        @if(session('error'))
            <div class="pwd__alert pwd__alert--danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="pwd__alert pwd__alert--danger">
                <ul class="pwd__list">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="pwd__card">
            <form method="post" action="{{ route('user.change.password') }}" class="pwd__form">
                @csrf
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="current_password">@lang('Current password')</label>
                    <input type="password" id="current_password" class="pwd__input" name="current_password" required autocomplete="current-password" placeholder="@lang('Enter current password')">
                </div>
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="new_password">@lang('New password')</label>
                    <input type="password" id="new_password" class="pwd__input @if(gs('secure_password')) secure-password @endif" name="password" required autocomplete="new-password" placeholder="@lang('Enter new password')">
                </div>
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="password_confirmation">@lang('Confirm new password')</label>
                    <input type="password" id="password_confirmation" class="pwd__input" name="password_confirmation" required autocomplete="new-password" placeholder="@lang('Confirm new password')">
                </div>
                <div class="pwd__actions">
                    <button type="submit" class="pwd__btn">@lang('Reset password')</button>
                    <a href="{{ route('user.profile.setting') }}" class="pwd__link">@lang('Back to profile')</a>
                </div>
            </form>
        </div>
    </div>
</div>

@if(gs('secure_password'))
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@endif
@endsection

@push('style')
<style>
.pwd {
    padding: 1.5rem 0 3rem;
    min-height: 60vh;
}
.pwd__container {
    max-width: 440px;
    margin: 0 auto;
    padding: 0 1rem;
}
@media (min-width: 768px) {
    .pwd__container { padding: 0 1.5rem; }
}

.pwd__head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.pwd__title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}
.pwd__sub {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0;
}
.pwd__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6366f1;
    text-decoration: none;
}
.pwd__back:hover { color: #4f46e5; }

.pwd__alert {
    padding: 0.875rem 1rem;
    border-radius: 12px;
    margin-bottom: 1.25rem;
    font-size: 0.9375rem;
}
.pwd__alert--success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.pwd__alert--danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.pwd__list { margin: 0; padding-left: 1.25rem; }

.pwd__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.04);
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
    padding: 1.75rem 1.5rem;
}
.pwd__form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.pwd__input-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.pwd__label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}
.pwd__input {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #0f172a;
    transition: border-color .2s, box-shadow .2s;
}
.pwd__input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
}
.pwd__input::placeholder { color: #94a3b8; }

.pwd__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    margin-top: 0.5rem;
}
.pwd__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    font-size: 0.9375rem;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    transition: opacity .2s;
}
.pwd__btn:hover { color: #fff; opacity: .95; }
.pwd__link {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6366f1;
    text-decoration: none;
}
.pwd__link:hover { color: #4f46e5; }
</style>
@endpush
