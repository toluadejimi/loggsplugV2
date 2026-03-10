<?php $__env->startSection('content'); ?>
<div class="pset">
    <div class="pset__container">
        <div class="pset__head">
            <div>
                <h1 class="pset__title"><?php echo app('translator')->get('Profile Setting'); ?></h1>
                <p class="pset__sub"><?php echo app('translator')->get('Manage your account details and password.'); ?></p>
            </div>
            <a href="<?php echo e(route('user.home')); ?>" class="pset__back"><i class="las la-arrow-left"></i> <?php echo app('translator')->get('Dashboard'); ?></a>
        </div>

        <?php if(session('notify')): ?>
            <?php $__currentLoopData = session('notify'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="pset__alert pset__alert--<?php echo e($n[0] === 'error' ? 'danger' : 'success'); ?>" role="alert">
                    <?php echo e(__($n[1])); ?>

                    <button type="button" class="pset__alert-close" data-bs-dismiss="alert" aria-label="Close"><i class="las la-times"></i></button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <?php if(session('message')): ?>
            <div class="pset__alert pset__alert--success"><?php echo e(session('message')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="pset__alert pset__alert--danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="pset__alert pset__alert--danger">
                <ul class="pset__list">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <div class="pset__card">
            <h2 class="pset__card-title"><i class="las la-user-circle"></i> <?php echo app('translator')->get('Account info'); ?></h2>
            <div class="pset__grid">
                <div class="pset__field">
                    <span class="pset__label"><?php echo app('translator')->get('Username'); ?></span>
                    <span class="pset__value"><?php echo e($user->username); ?></span>
                </div>
                <div class="pset__field">
                    <span class="pset__label"><?php echo app('translator')->get('Email'); ?></span>
                    <span class="pset__value"><?php echo e($user->email); ?></span>
                </div>
                <div class="pset__field">
                    <span class="pset__label"><?php echo app('translator')->get('Full name'); ?></span>
                    <span class="pset__value"><?php echo e($user->fullname); ?></span>
                </div>
                <?php if($user->mobile): ?>
                <div class="pset__field">
                    <span class="pset__label"><?php echo app('translator')->get('Mobile'); ?></span>
                    <span class="pset__value"><?php echo e($user->mobile); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="pset__card">
            <h2 class="pset__card-title"><i class="las la-pencil-alt"></i> <?php echo app('translator')->get('Update profile'); ?></h2>
            <form method="post" action="<?php echo e(route('user.profile.setting')); ?>" class="pset__form">
                <?php echo csrf_field(); ?>
                <div class="pset__grid">
                    <div class="pset__input-wrap">
                        <label class="pset__label" for="firstname"><?php echo app('translator')->get('First name'); ?></label>
                        <input type="text" id="firstname" class="pset__input" name="firstname" value="<?php echo e(old('firstname', $user->firstname)); ?>" required>
                    </div>
                    <div class="pset__input-wrap">
                        <label class="pset__label" for="lastname"><?php echo app('translator')->get('Last name'); ?></label>
                        <input type="text" id="lastname" class="pset__input" name="lastname" value="<?php echo e(old('lastname', $user->lastname)); ?>" required>
                    </div>
                </div>
                <div class="pset__input-wrap">
                    <label class="pset__label" for="address"><?php echo app('translator')->get('Address'); ?></label>
                    <input type="text" id="address" class="pset__input" name="address" value="<?php echo e(old('address', @$user->address->address)); ?>" placeholder="<?php echo app('translator')->get('Street address'); ?>">
                </div>
                <div class="pset__grid pset__grid--3">
                    <div class="pset__input-wrap">
                        <label class="pset__label" for="city"><?php echo app('translator')->get('City'); ?></label>
                        <input type="text" id="city" class="pset__input" name="city" value="<?php echo e(old('city', @$user->address->city)); ?>">
                    </div>
                    <div class="pset__input-wrap">
                        <label class="pset__label" for="state"><?php echo app('translator')->get('State'); ?></label>
                        <input type="text" id="state" class="pset__input" name="state" value="<?php echo e(old('state', @$user->address->state)); ?>">
                    </div>
                    <div class="pset__input-wrap">
                        <label class="pset__label" for="zip"><?php echo app('translator')->get('Zip code'); ?></label>
                        <input type="text" id="zip" class="pset__input" name="zip" value="<?php echo e(old('zip', @$user->address->zip)); ?>">
                    </div>
                </div>
                <button type="submit" class="pset__btn pset__btn--primary"><?php echo app('translator')->get('Save profile'); ?></button>
            </form>
        </div>

        
        <div class="pset__card" id="password">
            <h2 class="pset__card-title"><i class="las la-lock"></i> <?php echo app('translator')->get('Change password'); ?></h2>
            <form method="post" action="<?php echo e(route('user.change.password')); ?>" class="pset__form">
                <?php echo csrf_field(); ?>
                <div class="pset__input-wrap">
                    <label class="pset__label" for="current_password"><?php echo app('translator')->get('Current password'); ?></label>
                    <input type="password" id="current_password" class="pset__input" name="current_password" required autocomplete="current-password">
                </div>
                <div class="pset__input-wrap">
                    <label class="pset__label" for="new_password"><?php echo app('translator')->get('New password'); ?></label>
                    <input type="password" id="new_password" class="pset__input <?php if(gs('secure_password')): ?> secure-password <?php endif; ?>" name="password" required autocomplete="new-password">
                </div>
                <div class="pset__input-wrap">
                    <label class="pset__label" for="password_confirmation"><?php echo app('translator')->get('Confirm new password'); ?></label>
                    <input type="password" id="password_confirmation" class="pset__input" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="pset__btn pset__btn--primary"><?php echo app('translator')->get('Reset password'); ?></button>
            </form>
        </div>
    </div>
</div>

<?php if(gs('secure_password')): ?>
<?php $__env->startPush('script-lib'); ?>
<script src="<?php echo e(asset('assets/global/js/secure_password.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
.pset {
    padding: 1.5rem 0 3rem;
    min-height: 60vh;
}
.pset__container {
    max-width: 720px;
    margin: 0 auto;
    padding: 0 1rem;
}
@media (min-width: 768px) {
    .pset__container { padding: 0 1.5rem; }
}

.pset__head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.pset__title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}
.pset__sub {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0;
}
.pset__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6366f1;
    text-decoration: none;
}
.pset__back:hover { color: #4f46e5; }

.pset__alert {
    padding: 0.875rem 1rem;
    border-radius: 12px;
    margin-bottom: 1.25rem;
    font-size: 0.9375rem;
    position: relative;
    padding-right: 2.5rem;
}
.pset__alert--success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.pset__alert--danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.pset__alert-close {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: inherit;
    opacity: .7;
    cursor: pointer;
    padding: 4px;
}
.pset__list { margin: 0; padding-left: 1.25rem; }

.pset__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.04);
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
    padding: 1.5rem 1.5rem;
    margin-bottom: 1.25rem;
}
.pset__card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.pset__card-title i { color: #6366f1; }

.pset__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem 1.25rem;
}
@media (max-width: 575.98px) {
    .pset__grid { grid-template-columns: 1fr; }
}
.pset__grid--3 {
    grid-template-columns: 1fr 1fr 1fr;
}
@media (max-width: 575.98px) {
    .pset__grid--3 { grid-template-columns: 1fr; }
}

.pset__field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.pset__label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #64748b;
}
.pset__value {
    font-size: 0.9375rem;
    font-weight: 500;
    color: #0f172a;
}

.pset__form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.pset__input-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.pset__input {
    width: 100%;
    padding: 0.65rem 1rem;
    font-size: 0.9375rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #0f172a;
    transition: border-color .2s, box-shadow .2s;
}
.pset__input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
}
.pset__input::placeholder { color: #94a3b8; }

.pset__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.65rem 1.5rem;
    font-size: 0.9375rem;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: background .2s, transform .05s;
}
.pset__btn--primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff;
    align-self: flex-start;
}
.pset__btn--primary:hover { color: #fff; opacity: .95; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
(function($) {
    "use strict";
    $('.pset__alert-close').on('click', function() {
        $(this).closest('.pset__alert').fadeOut(200, function() { $(this).remove(); });
    });
})(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/profile_setting.blade.php ENDPATH**/ ?>