<?php $__env->startSection('content'); ?>
<div class="reseller-page">
    <div class="row g-4 p-3 p-md-4">
        <div class="col-lg-3">
            <div class="reseller-sidebar">
                <div class="reseller-sidebar__inner">
                    <button type="button" class="reseller-sidebar__close d-lg-none"><i class="las la-times"></i></button>
                    <div class="reseller-user-card">
                        <div class="reseller-user-card__avatar"><i class="las la-user"></i></div>
                        <h6 class="reseller-user-card__name"><?php echo e($user->fullname); ?></h6>
                        <p class="reseller-user-card__meta"><?php echo e($user->email); ?></p>
                        <a href="<?php echo e(route('user.profile.setting')); ?>" class="reseller-user-card__link"><i class="las la-pencil-alt"></i> <?php echo app('translator')->get('Edit profile'); ?></a>
                    </div>
                    <nav class="reseller-nav">
                        <span class="reseller-nav__title"><?php echo app('translator')->get('Menu'); ?></span>
                        <a href="<?php echo e(route('user.home')); ?>" class="reseller-nav__item"><i class="las la-th-large"></i> <?php echo app('translator')->get('Dashboard'); ?></a>
                        <a href="<?php echo e(route('user.reseller.index')); ?>" class="reseller-nav__item active"><i class="las la-store"></i> <?php echo app('translator')->get('Reseller'); ?></a>
                        <a href="<?php echo e(route('user.reseller.api-docs')); ?>" class="reseller-nav__item"><i class="las la-book"></i> <?php echo app('translator')->get('API Docs'); ?></a>
                        <a href="<?php echo e(route('products')); ?>" class="reseller-nav__item"><i class="las la-shopping-bag"></i> <?php echo app('translator')->get('Products'); ?></a>
                        <a href="<?php echo e(route('user.logout')); ?>" class="reseller-nav__item"><i class="las la-sign-out-alt"></i> <?php echo app('translator')->get('Logout'); ?></a>
                    </nav>
                </div>
            </div>
            <div class="d-lg-none mt-2">
                <button type="button" class="btn btn-outline-dark w-100 show-reseller-sidebar"><i class="las la-bars me-2"></i><?php echo app('translator')->get('Menu'); ?></button>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="reseller-hero reseller-hero--centered mb-4">
                <div class="reseller-hero__badge">Reseller Program</div>
                <h1 class="reseller-hero__title"><?php echo app('translator')->get('Reseller'); ?></h1>
                <p class="reseller-hero__sub mx-auto">Generate your own API key and download your reseller website, or pay for Web installation on your server.</p>
            </div>

            <?php if(session('notify')): ?>
                <?php $__currentLoopData = session('notify'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="alert alert-<?php echo e($n[0] === 'error' ? 'danger' : 'success'); ?> alert-dismissible fade show" role="alert"><?php echo e(__($n[1])); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            
            <div class="reseller-card reseller-card--highlight mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading mb-2"><i class="las la-key me-2"></i><?php echo app('translator')->get('Generate your own API key'); ?></h3>
                    <p class="reseller-card__desc mb-3">Create your reseller account instantly. You can then use the API and download your own reseller website.</p>
                    <form method="post" action="<?php echo e(route('user.reseller.generate-key')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-lg"><i class="las la-plus me-2"></i><?php echo app('translator')->get('Generate API key'); ?></button>
                    </form>
                </div>
            </div>

            <div class="reseller-card reseller-card--form mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading mb-3"><i class="las la-server me-2"></i><?php echo app('translator')->get('Do you need Web installation? Pay 20k for installation on your server'); ?></h3>
                    <p class="reseller-card__desc mb-4">Submit your details and we’ll contact you for payment (20,000) and setup on your server.</p>
                    <form method="post" action="<?php echo e(route('user.reseller.become.submit')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo app('translator')->get('Business name'); ?></label>
                                <input type="text" name="business_name" class="form-control" value="<?php echo e(old('business_name')); ?>" required placeholder="Your business or brand name">
                                <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger small"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo app('translator')->get('Contact name'); ?></label>
                                <input type="text" name="contact_name" class="form-control" value="<?php echo e(old('contact_name', $user->fullname)); ?>" required>
                                <?php $__errorArgs = ['contact_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger small"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo app('translator')->get('Contact email'); ?></label>
                                <input type="email" name="contact_email" class="form-control" value="<?php echo e(old('contact_email', $user->email)); ?>" required>
                                <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger small"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo app('translator')->get('Contact phone'); ?></label>
                                <input type="text" name="contact_phone" class="form-control" value="<?php echo e(old('contact_phone')); ?>" placeholder="Optional">
                                <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger small"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label"><?php echo app('translator')->get('Message'); ?></label>
                                <textarea name="message" class="form-control" rows="3" placeholder="Tell us about your business or how you plan to resell (optional)"><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger small"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark btn-lg"><i class="las la-paper-plane me-2"></i><?php echo app('translator')->get('Request Web installation'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('style'); ?>
<style>
.reseller-page { min-height: 60vh; }
.reseller-sidebar { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
.reseller-sidebar__inner { padding: 1.25rem; position: relative; }
.reseller-sidebar__close { position: absolute; top: 12px; right: 12px; background: #eee; border: none; width: 36px; height: 36px; border-radius: 8px; font-size: 1.2rem; }
.reseller-user-card { text-align: center; padding: 1rem 0; border-bottom: 1px solid #eee; }
.reseller-user-card__avatar { width: 56px; height: 56px; margin: 0 auto 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; }
.reseller-user-card__name { font-weight: 600; margin: 0; font-size: 1rem; }
.reseller-user-card__meta { font-size: 0.8rem; color: #6c757d; margin: 0 0 8px; }
.reseller-user-card__link { font-size: 0.85rem; color: #667eea; text-decoration: none; }
.reseller-nav { padding-top: 1rem; }
.reseller-nav__title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; padding: 0 0 8px; display: block; }
.reseller-nav__item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #333; text-decoration: none; font-size: 0.95rem; transition: background .15s; }
.reseller-nav__item i { font-size: 1.2rem; opacity: .8; }
.reseller-nav__item:hover { background: #f5f5f5; color: #333; }
.reseller-nav__item.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
.reseller-hero__title { font-size: 1.75rem; font-weight: 700; margin: 0 0 6px; background: linear-gradient(270deg, #D0CCA1 9%, #DD553E 43%, #3219E3 87%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.reseller-hero__sub { color: #6c757d; margin: 0; font-size: 0.95rem; max-width: 540px; }
.reseller-hero--centered { text-align: center; }
.reseller-hero__badge { display: inline-block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: #667eea; margin-bottom: 10px; }
.reseller-option-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #f0f0f0; padding: 1.5rem; height: 100%; transition: box-shadow .2s, transform .2s; }
.reseller-option-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
.reseller-option-card__icon { width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 12px; }
.reseller-option-card__title { font-size: 1.1rem; font-weight: 600; margin: 0 0 8px; }
.reseller-option-card__text { font-size: 0.875rem; color: #6c757d; margin: 0; line-height: 1.5; }
.reseller-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #f0f0f0; padding: 1.5rem; }
.reseller-card--highlight { border-color: #e8e0f0; background: linear-gradient(135deg, #faf8ff 0%, #fff 100%); }
.reseller-card--form .form-control { border-radius: 8px; border: 1px solid #e0e0e0; }
.reseller-card--form .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15); }
.reseller-card__heading { font-size: 1.25rem; font-weight: 600; margin: 0; }
.reseller-card__desc { font-size: 0.9rem; color: #6c757d; margin: 0; }
@media (max-width: 991px) {
    .reseller-sidebar { position: fixed; left: 0; top: 0; width: 280px; height: 100vh; z-index: 1050; transform: translateX(-100%); transition: transform .25s; overflow-y: auto; }
    .reseller-sidebar.show { transform: translateX(0); }
    .reseller-sidebar__inner { padding-top: 3rem; }
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
<script>
(function($){
    "use strict";
    $(".show-reseller-sidebar").on("click", function(){ $(".reseller-sidebar").addClass("show"); $(".sidebar-overlay").length || $("body").append('<div class="sidebar-overlay"></div>'); $(".sidebar-overlay").addClass("show"); });
    $(".reseller-sidebar__close, .sidebar-overlay").on("click", function(){ $(".reseller-sidebar").removeClass("show"); $(".sidebar-overlay").removeClass("show"); });
})(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/reseller/become.blade.php ENDPATH**/ ?>