<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Order ID'); ?></th>
                                <th><?php echo app('translator')->get('Product'); ?></th>
                                <th><?php echo app('translator')->get('Amount'); ?></th>
                                <th><?php echo app('translator')->get('Date'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>#<?php echo e($order->id); ?></td>
                                    <td><?php echo e($order->orderItems->first()->product->name ?? '-'); ?></td>
                                    <td><?php echo e($general->cur_sym); ?><?php echo e(showAmount($order->total_amount)); ?></td>
                                    <td><?php echo e(showDateTime($order->created_at)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($orders->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($orders)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('admin.resellers.index')); ?>" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> <?php echo app('translator')->get('Back to Resellers'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/resellers/orders.blade.php ENDPATH**/ ?>