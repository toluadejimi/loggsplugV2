<?php
    $text = $product->name . ' | ' . strLimit(strip_tags($product->description), 20);
?>
<div class="product-card card border-0 shadow-sm mb-3 overflow-hidden">
    <div class="card-body p-3">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <a href="<?php echo e(route('product.details', $product->id)); ?>" class="product-card__img-link d-block">
                    <img src="<?php echo e(url('')); ?>/assets/images/product/<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="product-card__img rounded">
                </a>
            </div>
            <div class="col">
                <a href="<?php echo e(route('product.details', $product->id)); ?>" class="product-card__title text-dark text-decoration-none d-block mb-1">
                    <?php echo e($text); ?>

                </a>
                <div class="product-card__meta d-flex flex-wrap align-items-center gap-2">
                    <span class="product-card__pill product-card__pill--price"><?php echo e($general->cur_sym); ?><?php echo e(showAmount($product->price)); ?></span>
                    <span class="product-card__pill product-card__pill--stock"><?php echo e($product->in_stock); ?> pcs</span>
                </div>
            </div>
            <div class="col-auto">
                <?php if($product->in_stock == 0): ?>
                    <span class="badge bg-secondary">Out of stock</span>
                <?php else: ?>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="/product/details/<?php echo e($product->id); ?>" class="product-card__btn btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1">
                            <i class="las la-shopping-cart"></i>
                            <span>View</span>
                        </a>
                    <?php else: ?>
                        <a href="/user/login" class="product-card__btn product-card__btn--lock btn btn-outline-dark btn-sm rounded-pill px-3">
                            <i class="las la-lock"></i> Login
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/partials/products.blade.php ENDPATH**/ ?>