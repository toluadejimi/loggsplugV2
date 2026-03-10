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
                        <a href="<?php echo e(route('user.reseller.index')); ?>" class="reseller-nav__item"><i class="las la-store"></i> <?php echo app('translator')->get('Reseller'); ?></a>
                        <a href="<?php echo e(route('user.reseller.api-docs')); ?>" class="reseller-nav__item active"><i class="las la-book"></i> <?php echo app('translator')->get('API Docs'); ?></a>
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
                <h1 class="reseller-hero__title"><?php echo app('translator')->get('Reseller API Documentation'); ?></h1>
                <p class="reseller-hero__sub">Use your API key to list products, place orders, and manage your reseller account programmatically.</p>
            </div>

            <div class="reseller-card mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading">Base URL</h3>
                    <p class="reseller-card__desc mb-2">All endpoints are relative to:</p>
                    <code class="reseller-apikey__value d-block py-2 px-3 rounded"><?php echo e($baseUrl); ?>/reseller</code>
                </div>
            </div>

            <div class="reseller-card mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading">Authentication</h3>
                    <p class="reseller-card__desc">Send your API key with every request using one of:</p>
                    <ul class="mb-2">
                        <li><strong>Header:</strong> <code>X-Api-Key: your_api_key</code></li>
                        <li><strong>Header:</strong> <code>Authorization: Bearer your_api_key</code></li>
                        <li><strong>Body (POST/PUT):</strong> <code>api_key: your_api_key</code></li>
                    </ul>
                    <p class="reseller-card__desc mb-0 small text-muted">You can generate or copy your key from the <a href="<?php echo e(route('user.reseller.index')); ?>">Reseller</a> page.</p>
                </div>
            </div>

            <div class="reseller-card mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading">GET /reseller/products</h3>
                    <p class="reseller-card__desc">Returns all active products with reseller price and stock.</p>
                    <p class="small text-muted mb-2">Example response:</p>
                    <pre class="bg-light p-3 rounded small mb-0" style="overflow-x:auto;">{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "category": "Category Name",
      "base_price": 100,
      "reseller_price": 95,
      "in_stock": 50
    }
  ]
}</pre>
                </div>
            </div>

            <div class="reseller-card mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading">POST /reseller/order</h3>
                    <p class="reseller-card__desc">Place an order. Your balance is charged at reseller price; you receive delivered account details.</p>
                    <p class="small text-muted mb-1">Body (JSON or form):</p>
                    <ul class="mb-2">
                        <li><code>product_id</code> (required) — Product ID</li>
                        <li><code>qty</code> (required) — Quantity (1–100)</li>
                    </ul>
                    <p class="small text-muted mb-2">Example response:</p>
                    <pre class="bg-light p-3 rounded small mb-0" style="overflow-x:auto;">{
  "success": true,
  "message": "Order completed.",
  "order_id": 123,
  "charged": 95,
  "delivered": [
    { "id": 1, "details": "account data..." }
  ]
}</pre>
                </div>
            </div>

            <div class="reseller-card mb-4">
                <div class="reseller-card__body">
                    <h3 class="reseller-card__heading">GET /reseller/me</h3>
                    <p class="reseller-card__desc">Returns your reseller profile: username, email, balance, business name, and discount.</p>
                    <p class="small text-muted mb-2">Example response:</p>
                    <pre class="bg-light p-3 rounded small mb-0" style="overflow-x:auto;">{
  "success": true,
  "data": {
    "username": "reseller1",
    "email": "reseller@example.com",
    "balance": 1000.50,
    "business_name": "My Shop",
    "admin_discount_percent": 5
  }
}</pre>
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
.reseller-nav__item:hover { background: #f5f5f5; color: #333; }
.reseller-nav__item.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
.reseller-hero__title { font-size: 1.75rem; font-weight: 700; margin: 0 0 6px; background: linear-gradient(270deg, #D0CCA1 9%, #DD553E 43%, #3219E3 87%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.reseller-hero__sub { color: #6c757d; margin: 0; font-size: 0.95rem; }
.reseller-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #f0f0f0; padding: 1.5rem; }
.reseller-card__heading { font-size: 1.1rem; font-weight: 600; margin: 0 0 6px; }
.reseller-card__desc { font-size: 0.875rem; color: #6c757d; margin: 0 0 0.5rem; line-height: 1.45; }
.reseller-apikey__value { font-size: 0.85rem; background: #f8f9fa; border: 1px solid #eee; }
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

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/reseller/api-docs.blade.php ENDPATH**/ ?>