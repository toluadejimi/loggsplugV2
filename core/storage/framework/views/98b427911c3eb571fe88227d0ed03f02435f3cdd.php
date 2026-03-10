<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th><?php echo app('translator')->get('User'); ?></th>
                                <th><?php echo app('translator')->get('Business'); ?></th>
                                <th><?php echo app('translator')->get('Discount %'); ?></th>
                                <th><?php echo app('translator')->get('Balance'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('API Key'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $resellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?php echo e($r->user->username ?? '-'); ?></span>
                                        <br><span class="small"><?php echo e($r->user->email ?? ''); ?></span>
                                    </td>
                                    <td><?php echo e($r->business_name ?: '-'); ?></td>
                                    <td><?php echo e($r->admin_discount_percent); ?>%</td>
                                    <td><?php echo e($general->cur_sym); ?><?php echo e(showAmount($r->user->balance ?? 0)); ?></td>
                                    <td>
                                        <?php if($r->status == Status::ENABLE && !$r->api_key_revoked_at): ?>
                                            <span class="badge badge--success"><?php echo app('translator')->get('Active'); ?></span>
                                        <?php elseif($r->api_key_revoked_at): ?>
                                            <span class="badge badge--danger"><?php echo app('translator')->get('Key Revoked'); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge--warning"><?php echo app('translator')->get('Suspended'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <code class="small"><?php echo e(Str::limit($r->api_key, 20)); ?></code>
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="<?php echo e(route('admin.resellers.edit', $r->id)); ?>" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i> <?php echo app('translator')->get('Edit'); ?>
                                            </a>
                                            <a href="<?php echo e(route('admin.resellers.orders', $r->id)); ?>" class="btn btn-sm btn-outline--info">
                                                <i class="las la-list"></i> <?php echo app('translator')->get('Orders'); ?>
                                            </a>
                                        </div>
                                    </td>
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
                <?php if($resellers->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($resellers)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('admin.resellers.create')); ?>" class="btn btn-sm btn--primary">
        <i class="las la-plus"></i> <?php echo app('translator')->get('Add Reseller'); ?>
    </a>
    <a href="<?php echo e(url('/reseller-site/download')); ?>" class="btn btn-sm btn--success" target="_blank">
        <i class="las la-download"></i> <?php echo app('translator')->get('Download Mini-Site Template'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/resellers/index.blade.php ENDPATH**/ ?>