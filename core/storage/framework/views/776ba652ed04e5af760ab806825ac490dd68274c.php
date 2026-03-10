<?php $__env->startSection('content'); ?>
    <div class="py-120">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        echo $policy->data_values->details;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/policy.blade.php ENDPATH**/ ?>