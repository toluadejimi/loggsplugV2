<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $products = $category->products; ?>

    <div class="category-block mb-4">
        <div class="category-block__header mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="category-block__title mb-0"><?php echo e(__($category->name)); ?></h6>
            <a href="<?php echo e(route('category.products', ['slug' => $category->name, 'id' => $category->id])); ?>" class="category-block__view-all text-decoration-none">
                View all <i class="las la-arrow-right"></i>
            </a>
        </div>

        <div class="category-block__products">
            <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make($activeTemplate . 'partials.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>








































<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/partials/category_loop.blade.php ENDPATH**/ ?>