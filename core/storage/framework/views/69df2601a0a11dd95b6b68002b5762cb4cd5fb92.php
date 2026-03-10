<?php $__env->startSection('content'); ?>
<div class="reseller-page">
    <div class="row g-4 p-3 p-md-4">
        <div class="col-lg-3">
            <div class="reseller-sidebar">
                <div class="reseller-sidebar__inner">
                    <button type="button" class="reseller-sidebar__close d-lg-none"><i class="las la-times"></i></button>
                    <div class="reseller-user-card">
                        <div class="reseller-user-card__avatar">
                            <i class="las la-user"></i>
                        </div>
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
            <div class="reseller-hero mb-4">
                <h1 class="reseller-hero__title"><?php echo app('translator')->get('Reseller Dashboard'); ?></h1>
                <p class="reseller-hero__sub">Manage your API key, mini-site, and payouts in one place.</p>
            </div>

            <?php if(session('notify')): ?>
                <?php $__currentLoopData = session('notify'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="alert alert-<?php echo e($n[0] === 'error' ? 'danger' : 'success'); ?> alert-dismissible fade show" role="alert"><?php echo e(__($n[1])); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <div class="row g-4">
                
                <div class="col-12">
                    <div class="reseller-card">
                        <div class="reseller-card__icon reseller-card__icon--primary"><i class="las la-key"></i></div>
                        <div class="reseller-card__body">
                            <h3 class="reseller-card__heading"><?php echo app('translator')->get('Connect with API Key'); ?></h3>
                            <p class="reseller-card__desc">Use this key in your app or mini website. Keep it private.</p>
                            <div class="reseller-apikey">
                                <code id="apiKeyDisplay" class="reseller-apikey__value"><?php echo e($reseller->api_key); ?></code>
                                <button type="button" class="btn btn-primary reseller-apikey__btn" id="copyApiKey"><i class="las la-copy me-1"></i><?php echo app('translator')->get('Copy'); ?></button>
                            </div>
                            <form method="post" action="<?php echo e(route('user.reseller.generate-key')); ?>" class="mt-2 js-regenerate-key-form">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="las la-sync me-1"></i><?php echo app('translator')->get('Regenerate API key'); ?></button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="reseller-card">
                        <div class="reseller-card__icon reseller-card__icon--success"><i class="las la-download"></i></div>
                        <div class="reseller-card__body">
                            <h3 class="reseller-card__heading"><?php echo app('translator')->get('Download your own reseller website'); ?></h3>
                            <p class="reseller-card__desc">ZIP package with your API key pre-filled. Set business name and logo in config.</p>
                            <a href="<?php echo e(route('reseller-site.download')); ?>?api_key=<?php echo e(urlencode($reseller->api_key)); ?>" class="btn btn-success w-100 mb-2"><i class="las la-file-archive me-2"></i><?php echo app('translator')->get('Download ZIP'); ?></a>
                            <a href="<?php echo e(route('user.reseller.api-docs')); ?>" class="btn btn-outline-primary btn-sm w-100"><i class="las la-book me-1"></i><?php echo app('translator')->get('API Documentation'); ?></a>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="reseller-card reseller-card--highlight">
                        <div class="reseller-card__icon reseller-card__icon--warning"><i class="las la-cog"></i></div>
                        <div class="reseller-card__body flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h3 class="reseller-card__heading mb-0"><?php echo app('translator')->get('Do you need Web installation? Pay 20k for installation on your server'); ?></h3>
                                <span class="badge bg-dark"><?php echo e($general->cur_sym ?? '₦'); ?>20,000</span>
                            </div>
                            <p class="reseller-card__desc">Submit your details and we’ll contact you for payment and setup on your server.</p>
                            <form method="post" action="<?php echo e(route('user.reseller.pro-install.submit')); ?>" class="reseller-form">
                                <?php echo csrf_field(); ?>
                                <div class="row g-2">
                                    <div class="col-md-6"><label class="form-label small text-muted"><?php echo app('translator')->get('Business name'); ?></label><input type="text" name="business_name" class="form-control form-control-sm" value="<?php echo e(old('business_name', $reseller->business_name)); ?>" required></div>
                                    <div class="col-md-6"><label class="form-label small text-muted"><?php echo app('translator')->get('Contact name'); ?></label><input type="text" name="contact_name" class="form-control form-control-sm" value="<?php echo e(old('contact_name', $user->fullname)); ?>" required></div>
                                    <div class="col-md-6"><label class="form-label small text-muted"><?php echo app('translator')->get('Contact email'); ?></label><input type="email" name="contact_email" class="form-control form-control-sm" value="<?php echo e(old('contact_email', $user->email)); ?>" required></div>
                                    <div class="col-md-6"><label class="form-label small text-muted"><?php echo app('translator')->get('Contact phone'); ?></label><input type="text" name="contact_phone" class="form-control form-control-sm" value="<?php echo e(old('contact_phone')); ?>" placeholder="Optional"></div>
                                    <div class="col-12"><label class="form-label small text-muted"><?php echo app('translator')->get('Message'); ?></label><textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Optional"><?php echo e(old('message')); ?></textarea></div>
                                    <div class="col-12"><button type="submit" class="btn btn-dark"><?php echo app('translator')->get('Request Web installation'); ?></button></div>
                                </div>
                            </form>
                        </div>
                    </div>
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
.reseller-user-card__link:hover { color: #764ba2; }
.reseller-nav { padding-top: 1rem; }
.reseller-nav__title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; padding: 0 0 8px; display: block; }
.reseller-nav__item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #333; text-decoration: none; font-size: 0.95rem; transition: background .15s; }
.reseller-nav__item i { font-size: 1.2rem; opacity: .8; }
.reseller-nav__item:hover { background: #f5f5f5; color: #333; }
.reseller-nav__item.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
.reseller-hero__title { font-size: 1.75rem; font-weight: 700; margin: 0 0 6px; background: linear-gradient(270deg, #D0CCA1 9%, #DD553E 43%, #3219E3 87%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.reseller-hero__sub { color: #6c757d; margin: 0; font-size: 0.95rem; }
.reseller-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #f0f0f0; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-start; transition: box-shadow .2s; }
.reseller-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); }
.reseller-card--highlight { border-color: #e8e0f0; background: linear-gradient(135deg, #faf8ff 0%, #fff 100%); }
.reseller-card__icon { width: 48px; height: 48px; min-width: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; }
.reseller-card__icon--primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.reseller-card__icon--success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.reseller-card__icon--info { background: linear-gradient(135deg, #4f6bff 0%, #00d4ff 100%); }
.reseller-card__icon--warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.reseller-card__heading { font-size: 1.1rem; font-weight: 600; margin: 0 0 6px; }
.reseller-card__desc { font-size: 0.875rem; color: #6c757d; margin: 0 0 1rem; line-height: 1.45; }
.reseller-apikey { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.reseller-apikey__value { flex: 1; min-width: 180px; padding: 10px 14px; background: #f8f9fa; border-radius: 8px; font-size: 0.8rem; word-break: break-all; border: 1px solid #eee; }
.reseller-apikey__btn { white-space: nowrap; }
.reseller-input { border-radius: 8px; border: 1px solid #e0e0e0; }
.reseller-form .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15); }
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
    $('#copyApiKey').on('click', function(){
        var el = document.getElementById('apiKeyDisplay');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(el.textContent).then(function(){ if (typeof iziToast !== 'undefined') iziToast.success({ message: 'Copied!', position: 'topRight' }); });
        } else {
            var r = document.createRange(); r.selectNodeContents(el); window.getSelection().removeAllRanges(); window.getSelection().addRange(r);
            document.execCommand('copy');
            if (typeof iziToast !== 'undefined') iziToast.success({ message: 'Copied!', position: 'topRight' });
        }
    });
    $('.js-regenerate-key-form').on('submit', function(){ return confirm('Generate a new key? Your current key will stop working.'); });
    $(".show-reseller-sidebar").on("click", function(){ $(".reseller-sidebar").addClass("show"); $(".sidebar-overlay").length || $("body").append('<div class="sidebar-overlay"></div>'); $(".sidebar-overlay").addClass("show"); });
    $(".reseller-sidebar__close, .sidebar-overlay").on("click", function(){ $(".reseller-sidebar").removeClass("show"); $(".sidebar-overlay").removeClass("show"); });
})(jQuery);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/reseller/dashboard.blade.php ENDPATH**/ ?>