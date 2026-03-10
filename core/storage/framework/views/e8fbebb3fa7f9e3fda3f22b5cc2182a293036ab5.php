<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $products = $category->products; ?>

    <div class="catalog-item-wrapper mb-2">
        <div class="d-grid gap-2 mb-2">
            <strong>
                <p style="font-size: 11px; background: linear-gradient(90deg, #020c49 0%, #4855a6 100%); border-radius:10px; color: white"
                   class="p-2"><?php echo e(__($category->name)); ?></p>
            </strong>
        </div>
    </div>

    <div class="col-12">
        <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make($activeTemplate . 'partials.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>



    <div class="col-12 d-flex justify-content-end mb-4">
        <a href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
           class="btn btn-main btn-lg w-100 pill">
            <?php echo app('translator')->get('View All'); ?>
        </a>
    </div>








































<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/partials/category_loop.blade.php ENDPATH**/ ?>