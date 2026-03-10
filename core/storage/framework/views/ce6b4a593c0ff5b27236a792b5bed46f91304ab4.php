<?php $__env->startSection('content'); ?>
<div class="admin-login-page" style="background-image: url('<?php echo e(asset('assets/admin/images/login.jpg')); ?>')">
    <div class="admin-login-overlay"></div>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-login-card__header">
                <div class="admin-login-card__icon">
                    <i class="las la-shield-alt"></i>
                </div>
                <h1 class="admin-login-card__title"><?php echo app('translator')->get('Welcome to'); ?> <strong><?php echo e(__($general->site_name)); ?></strong></h1>
                <p class="admin-login-card__subtitle"><?php echo e(__($pageTitle)); ?> <?php echo app('translator')->get('to'); ?> <?php echo e(__($general->site_name)); ?> <?php echo app('translator')->get('Dashboard'); ?></p>
            </div>
            <div class="admin-login-card__body">
                <form action="<?php echo e(route('admin.login')); ?>" method="POST" class="admin-login-form verify-gcaptcha">
                    <?php echo csrf_field(); ?>
                    <div class="admin-login-form__group">
                        <label class="admin-login-form__label" for="admin-username"><?php echo app('translator')->get('Username'); ?></label>
                        <div class="admin-login-form__input-wrap">
                            <i class="las la-user admin-login-form__icon"></i>
                            <input type="text" id="admin-username" class="admin-login-form__input" value="<?php echo e(old('username')); ?>" name="username" placeholder="<?php echo app('translator')->get('Enter your username'); ?>" required autocomplete="username">
                        </div>
                    </div>
                    <div class="admin-login-form__group">
                        <label class="admin-login-form__label" for="admin-password"><?php echo app('translator')->get('Password'); ?></label>
                        <div class="admin-login-form__input-wrap">
                            <i class="las la-lock admin-login-form__icon"></i>
                            <input type="password" id="admin-password" class="admin-login-form__input" name="password" placeholder="<?php echo app('translator')->get('Enter your password'); ?>" required autocomplete="current-password">
                        </div>
                    </div>
                    <?php if (isset($component)) { $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243 = $component; } ?>
<?php $component = App\View\Components\Captcha::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('captcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Captcha::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243)): ?>
<?php $component = $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243; ?>
<?php unset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243); ?>
<?php endif; ?>
                    <div class="admin-login-form__options">
                        <label class="admin-login-form__remember">
                            <input type="checkbox" name="remember" id="remember" class="admin-login-form__checkbox">
                            <span class="admin-login-form__checkmark"></span>
                            <span class="admin-login-form__remember-text"><?php echo app('translator')->get('Remember Me'); ?></span>
                        </label>
                        <a href="<?php echo e(route('admin.password.reset')); ?>" class="admin-login-form__forgot"><?php echo app('translator')->get('Forgot Password?'); ?></a>
                    </div>
                    <button type="submit" class="admin-login-form__submit">
                        <span><?php echo app('translator')->get('Sign In'); ?></span>
                        <i class="las la-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
        <p class="admin-login-footer"><?php echo app('translator')->get('Secure admin access'); ?> &middot; <?php echo e(__($general->site_name)); ?></p>
    </div>
</div>

<?php $__env->startPush('style'); ?>
<style>
/* Admin Login - Professional theme */
.admin-login-page {
    --admin-login-primary: #4f46e5;
    --admin-login-primary-hover: #4338ca;
    --admin-login-primary-light: rgba(79, 70, 229, 0.08);
    --admin-login-card-bg: #ffffff;
    --admin-login-text: #1e293b;
    --admin-login-text-muted: #64748b;
    --admin-login-border: #e2e8f0;
    --admin-login-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --admin-login-radius: 16px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.admin-login-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(30, 41, 59, 0.88) 50%, rgba(15, 23, 42, 0.92) 100%);
    z-index: 0;
}
.admin-login-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
}
.admin-login-card {
    background: var(--admin-login-card-bg);
    border-radius: var(--admin-login-radius);
    box-shadow: var(--admin-login-shadow);
    overflow: hidden;
    animation: adminLoginCardIn 0.5s ease-out;
}
@keyframes adminLoginCardIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.admin-login-card__header {
    background: linear-gradient(135deg, var(--admin-login-primary) 0%, #6366f1 100%);
    padding: 2.25rem 2rem;
    text-align: center;
}
.admin-login-card__icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
}
.admin-login-card__icon i {
    font-size: 1.75rem;
    color: #fff;
}
.admin-login-card__title {
    font-size: 1.35rem;
    font-weight: 600;
    color: #fff;
    margin: 0 0 0.35rem;
    line-height: 1.35;
    letter-spacing: -0.02em;
}
.admin-login-card__title strong {
    font-weight: 700;
}
.admin-login-card__subtitle {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    font-weight: 400;
}
.admin-login-card__body {
    padding: 2rem 2rem 2.25rem;
}
.admin-login-form__group {
    margin-bottom: 1.25rem;
}
.admin-login-form__label {
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--admin-login-text);
    margin-bottom: 0.5rem;
}
.admin-login-form__input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.admin-login-form__icon {
    position: absolute;
    left: 1rem;
    font-size: 1.125rem;
    color: var(--admin-login-text-muted);
    pointer-events: none;
    transition: color 0.2s;
}
.admin-login-form__input {
    width: 100%;
    height: 50px;
    padding: 0 1rem 0 2.75rem;
    font-size: 0.9375rem;
    color: var(--admin-login-text);
    background: #f8fafc;
    border: 1px solid var(--admin-login-border);
    border-radius: 10px;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.admin-login-form__input::placeholder {
    color: #94a3b8;
}
.admin-login-form__input:hover {
    border-color: #cbd5e1;
}
.admin-login-form__input:focus {
    outline: none;
    border-color: var(--admin-login-primary);
    box-shadow: 0 0 0 3px var(--admin-login-primary-light);
    background: #fff;
}
.admin-login-form__input-wrap:focus-within .admin-login-form__icon {
    color: var(--admin-login-primary);
}
.admin-login-form__options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.admin-login-form__remember {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--admin-login-text-muted);
    user-select: none;
}
.admin-login-form__checkbox {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.admin-login-form__checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--admin-login-border);
    border-radius: 5px;
    flex-shrink: 0;
    transition: border-color 0.2s, background 0.2s;
    position: relative;
}
.admin-login-form__checkbox:checked + .admin-login-form__checkmark {
    background: var(--admin-login-primary);
    border-color: var(--admin-login-primary);
}
.admin-login-form__checkbox:checked + .admin-login-form__checkmark::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
.admin-login-form__forgot {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--admin-login-primary);
    text-decoration: none;
    transition: color 0.2s;
}
.admin-login-form__forgot:hover {
    color: var(--admin-login-primary-hover);
}
.admin-login-form__submit {
    width: 100%;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, var(--admin-login-primary) 0%, #6366f1 100%);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
    box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.4);
}
.admin-login-form__submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px 0 rgba(79, 70, 229, 0.45);
}
.admin-login-form__submit:active {
    transform: translateY(0);
}
.admin-login-form__submit i {
    font-size: 1.125rem;
    transition: transform 0.2s;
}
.admin-login-form__submit:hover i {
    transform: translateX(3px);
}
.admin-login-footer {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.6);
}
/* Captcha / form extras inside card */
.admin-login-form .form-group {
    margin-bottom: 1.25rem;
}
.admin-login-form .form-group label {
    color: var(--admin-login-text);
    font-weight: 600;
}
.admin-login-form .form-control {
    height: 50px;
    border: 1px solid var(--admin-login-border);
    border-radius: 10px;
    color: var(--admin-login-text);
    background: #f8fafc;
}
.admin-login-form .form-control:focus {
    border-color: var(--admin-login-primary);
    box-shadow: 0 0 0 3px var(--admin-login-primary-light);
}
@media (max-width: 480px) {
    .admin-login-card__header,
    .admin-login-card__body {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    .admin-login-card__title {
        font-size: 1.2rem;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>