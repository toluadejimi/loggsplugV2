<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.resellers.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('User'); ?></label>
                            <select name="user_id" class="form-control" required>
                                <option value=""><?php echo app('translator')->get('Select User'); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->id); ?>" <?php echo e(old('user_id') == $u->id ? 'selected' : ''); ?>>
                                        <?php echo e($u->username); ?> (<?php echo e($u->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Only users who are not already resellers are listed.</small>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Admin discount %'); ?></label>
                            <input type="number" step="0.01" min="0" max="99.99" name="admin_discount_percent" class="form-control" value="<?php echo e(old('admin_discount_percent', 0)); ?>" placeholder="0">
                            <small class="text-muted">Platform cut from base price. Reseller pays (100 - this)% of product price.</small>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Business name'); ?></label>
                            <input type="text" name="business_name" class="form-control" value="<?php echo e(old('business_name')); ?>" placeholder="Optional">
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Contact email'); ?></label>
                            <input type="email" name="contact_email" class="form-control" value="<?php echo e(old('contact_email')); ?>" placeholder="Optional, defaults to user email">
                        </div>
                        <button type="submit" class="btn btn--primary"><?php echo app('translator')->get('Create Reseller'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/admin/resellers/create.blade.php ENDPATH**/ ?>