<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $products = $category->products; ?>

    <div class="category-block mb-4">
        <div class="category-block__header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h6 class="category-block__title mb-0"><?php echo e(__($category->name)); ?></h6>
            <a href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
               class="category-block__view-all btn btn-sm btn-outline-primary rounded-pill">
                View all <i class="las la-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="category-block__products">
            <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make($activeTemplate . 'partials.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-2">
            <a href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
               class="btn btn-primary rounded-pill px-4">
                View all <?php echo e(__($category->name)); ?>

            </a>
        </div>
    </div>








































<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/partials/category_loop.blade.php ENDPATH**/ ?>