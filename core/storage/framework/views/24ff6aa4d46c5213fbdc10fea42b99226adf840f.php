<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-8">
            <?php if(session('new_api_key')): ?>
                <div class="alert alert-success">
                    <strong><?php echo app('translator')->get('New API key (save it; it will not be shown again):'); ?></strong>
                    <code class="d-block mt-2"><?php echo e(session('new_api_key')); ?></code>
                </div>
            <?php endif; ?>
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.resellers.update', $reseller->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('User'); ?></label>
                            <input type="text" class="form-control" value="<?php echo e($reseller->user->username ?? ''); ?> (<?php echo e($reseller->user->email ?? ''); ?>)" disabled>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Admin discount %'); ?></label>
                            <input type="number" step="0.01" min="0" max="99.99" name="admin_discount_percent" class="form-control" value="<?php echo e(old('admin_discount_percent', $reseller->admin_discount_percent)); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Status'); ?></label>
                            <select name="status" class="form-control">
                                <option value="1" <?php echo e($reseller->status == 1 ? 'selected' : ''); ?>><?php echo app('translator')->get('Active'); ?></option>
                                <option value="0" <?php echo e($reseller->status == 0 ? 'selected' : ''); ?>><?php echo app('translator')->get('Suspended'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Business name'); ?></label>
                            <input type="text" name="business_name" class="form-control" value="<?php echo e(old('business_name', $reseller->business_name)); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Contact email'); ?></label>
                            <input type="email" name="contact_email" class="form-control" value="<?php echo e(old('contact_email', $reseller->contact_email)); ?>">
                        </div>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Update'); ?></button>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><?php echo app('translator')->get('API Key'); ?></h6>
                    <p class="text-muted small"><?php echo app('translator')->get('Revoke to block API access. Regenerate to issue a new key (old key stops working).'); ?></p>
                    <div class="d-flex gap-2">
                        <form action="<?php echo e(route('admin.resellers.revoke-key', $reseller->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('<?php echo app('translator')->get('Revoke API key? Reseller will not be able to use the API until you regenerate.'); ?>');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline--danger btn-sm"><?php echo app('translator')->get('Revoke Key'); ?></button>
                        </form>
                        <form action="<?php echo e(route('admin.resellers.regenerate-key', $reseller->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('<?php echo app('translator')->get('Generate new key? Current key will stop working.'); ?>');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline--primary btn-sm"><?php echo app('translator')->get('Regenerate Key'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <a href="<?php echo e(route('admin.resellers.index')); ?>" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> <?php echo app('translator')->get('Back'); ?>
    </a>
    <a href="<?php echo e(route('admin.resellers.orders', $reseller->id)); ?>" class="btn btn-sm btn--primary">
        <i class="las la-list"></i> <?php echo app('translator')->get('Orders'); ?>
    </a>
    <a href="<?php echo e(url('/reseller-site/download?api_key=' . urlencode($reseller->api_key))); ?>" class="btn btn-sm btn--success" target="_blank">
        <i class="las la-download"></i> <?php echo app('translator')->get('Download Mini-Site'); ?>
    </a>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/resellers/edit.blade.php ENDPATH**/ ?>