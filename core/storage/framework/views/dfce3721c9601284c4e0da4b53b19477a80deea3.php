<?php $__env->startSection('panel'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <h5 class="card-title mb-4"><?php echo app('translator')->get('Reseller reported orders'); ?></h5>
                <p class="text-muted">Orders reported by resellers so you can replace the product. Open order details to handle replacement.</p>
            </div>
            <div class="table-responsive--sm table-responsive">
                <table class="table table--light style--two">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('Order ID'); ?></th>
                            <th><?php echo app('translator')->get('Reseller'); ?></th>
                            <th><?php echo app('translator')->get('Product'); ?></th>
                            <th><?php echo app('translator')->get('Reported at'); ?></th>
                            <th><?php echo app('translator')->get('Reason'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="fw-bold"><?php echo e($order->id); ?></span></td>
                                <td>
                                    <?php echo e($order->reseller->business_name ?? '—'); ?>

                                    <?php if($order->reseller && $order->reseller->user): ?>
                                        <br><span class="small text-muted"><?php echo e($order->reseller->user->email ?? ''); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($order->orderItems->first()->product->name ?? '—'); ?></td>
                                <td><?php echo e($order->reported_at ? showDateTime($order->reported_at) : '—'); ?></td>
                                <td><span class="small"><?php echo e($order->report_reason ?: '—'); ?></span></td>
                                <td>
                                    <a href="<?php echo e(route('admin.report.order.details', $order->id)); ?>" class="btn btn-sm btn-outline--primary">
                                        <i class="las la-desktop"></i> <?php echo app('translator')->get('View / Replace'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-muted text-center" colspan="6"><?php echo app('translator')->get('No reseller reported orders.'); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/reports/reseller_reported_orders.blade.php ENDPATH**/ ?>