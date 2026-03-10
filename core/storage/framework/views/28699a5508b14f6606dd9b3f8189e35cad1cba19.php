<?php $__env->startSection('content'); ?>

    <div class="container">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="my-3"><?php echo app('translator')->get('Current Password'); ?></label>
                            <input type="password" class="form-control" name="current_password" required
                                   autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="my-3"><?php echo app('translator')->get('Password'); ?></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control <?php if($general->secure_password): ?> secure-password <?php endif; ?>"
                                       name="password" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="my-3"><?php echo app('translator')->get('Confirm Password'); ?></label>
                            <input type="password" class="form-control" name="password_confirmation" required
                                   autocomplete="current-password">
                        </div>

                        <div class="footer fixed">

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger my-4">
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if(session()->has('message')): ?>
                                <div class="alert alert-success">
                                    <?php echo e(session()->get('message')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if(session()->has('error')): ?>
                                <div class="alert alert-danger mt-2">
                                    <?php echo e(session()->get('error')); ?>

                                </div>
                            <?php endif; ?>


                            <div class="container">


                                <button type="submit" style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color:#ffffff;"
                                   class="btn btn-primary text-start w-100 btn-rounded">

                                    Reset Password
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
        <?php $__env->stopSection(); ?>

        <?php if($general->secure_password): ?>
            <?php $__env->startPush('script-lib'); ?>
                <script src="<?php echo e(asset('assets/global/js/secure_password.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
    <?php endif; ?>

<?php echo $__env->make($activeTemplate.'layouts.nofooter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/user/password.blade.php ENDPATH**/ ?>