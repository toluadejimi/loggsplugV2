<?php $__env->startSection('content'); ?>
<div class="pwd">
    <div class="pwd__container">
        <div class="pwd__head">
            <div>
                <h1 class="pwd__title"><?php echo app('translator')->get('Change Password'); ?></h1>
                <p class="pwd__sub"><?php echo app('translator')->get('Choose a strong password to keep your account secure.'); ?></p>
            </div>
            <a href="<?php echo e(route('user.profile.setting')); ?>" class="pwd__back"><i class="las la-arrow-left"></i> <?php echo app('translator')->get('Profile'); ?></a>
        </div>

        <?php if(session('notify')): ?>
            <?php $__currentLoopData = session('notify'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="pwd__alert pwd__alert--<?php echo e($n[0] === 'error' ? 'danger' : 'success'); ?>" role="alert">
                    <?php echo e(__($n[1])); ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <?php if(session('message')): ?>
            <div class="pwd__alert pwd__alert--success"><?php echo e(session('message')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="pwd__alert pwd__alert--danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="pwd__alert pwd__alert--danger">
                <ul class="pwd__list">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="pwd__card">
            <form method="post" action="<?php echo e(route('user.change.password')); ?>" class="pwd__form">
                <?php echo csrf_field(); ?>
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="current_password"><?php echo app('translator')->get('Current password'); ?></label>
                    <input type="password" id="current_password" class="pwd__input" name="current_password" required autocomplete="current-password" placeholder="<?php echo app('translator')->get('Enter current password'); ?>">
                </div>
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="new_password"><?php echo app('translator')->get('New password'); ?></label>
                    <input type="password" id="new_password" class="pwd__input <?php if(gs('secure_password')): ?> secure-password <?php endif; ?>" name="password" required autocomplete="new-password" placeholder="<?php echo app('translator')->get('Enter new password'); ?>">
                </div>
                <div class="pwd__input-wrap">
                    <label class="pwd__label" for="password_confirmation"><?php echo app('translator')->get('Confirm new password'); ?></label>
                    <input type="password" id="password_confirmation" class="pwd__input" name="password_confirmation" required autocomplete="new-password" placeholder="<?php echo app('translator')->get('Confirm new password'); ?>">
                </div>
                <div class="pwd__actions">
                    <button type="submit" class="pwd__btn"><?php echo app('translator')->get('Reset password'); ?></button>
                    <a href="<?php echo e(route('user.profile.setting')); ?>" class="pwd__link"><?php echo app('translator')->get('Back to profile'); ?></a>
                </div>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/password.blade.php ENDPATH**/ ?>