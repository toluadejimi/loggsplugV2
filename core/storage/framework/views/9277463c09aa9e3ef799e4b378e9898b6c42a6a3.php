<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row gy-4 my-5">
            <div class="col-xl-12 col-sm-12">
                <div class="dashboard-widget">
                    <form action="<?php echo e(route('user.deposit.insert')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

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

                        <h6 class="mt-3 p-3">Pay Here</h6>
                        <p class="mt-3 p-3">Pay to the Account details below once payment is received your wallet will be funded</p>


                        <div class="p-3">
                            <div class="card-body">
                                <h6>Amount</h6>
                                <p>NGN <?php echo e(number_format($amount, 2 ?? 0.0)); ?></p>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6>Bank Account</h6>
                                <p><?php echo e($bank_name ?? "Not Available"); ?></p>
                            </div>
                        </div>

                        <!-- Gateway -->
                        <div class="p-3">
                            <div class="card-body">
                                <h6 class="mb-2">Account Name</h6>
                                <p><?php echo e($account_name ?? "Not Available"); ?></p>

                            </div>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6 class="mb-2">Account No</h6>
                                <p><?php echo e($account_no ?? "Not Available"); ?></p>
                            </div>
                        </div>

                        <!-- Extra fields (hidden by default) -->


                        <div class="p-3">

                            <a href="/products" type="button"
                                    style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color:#ffffff;"
                                    class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm"><?php echo app('translator')->get('Home'); ?>
                            </a>

                        </div>



                    </form>

                </div>
            </div>


        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/loggsplu/public_html/core/resources/views/templates/basic/user/point.blade.php ENDPATH**/ ?>