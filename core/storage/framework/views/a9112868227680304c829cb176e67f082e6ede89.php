<?php $__env->startSection('content'); ?>
<div class="py-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title text-center"><?php echo e(__($pageTitle)); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('user.data.submit')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('First Name'); ?></label>
                                    <input type="text" class="form--control" name="firstname" value="<?php echo e(old('firstname', auth()->user()->firstname)); ?>" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('Last Name'); ?></label>
                                    <input type="text" class="form--control" name="lastname" value="<?php echo e(old('lastname', auth()->user()->lastname)); ?>" required>
                                </div>
                                <?php if(auth()->user()->login_by): ?>
                                    <div class="col-md-12 form-group">
                                        <label class="form--label"><?php echo app('translator')->get('Email'); ?></label>
                                        <input type="email" class="form--control" name="email" value="<?php echo e(old('email', auth()->user()->email)); ?>" <?php if(auth()->user()->email): ?> readonly <?php endif; ?> required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form--label"><?php echo app('translator')->get('Country'); ?></label>
                                        <div class="form--select">
                                            <select name="country" class="form--control">
                                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option data-mobile_code="<?php echo e($country->dial_code); ?>" value="<?php echo e($country->country); ?>" data-code="<?php echo e($key); ?>"><?php echo e(__($country->country)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form--label required"><?php echo app('translator')->get('Mobile'); ?></label>
                                        <div class="input-group input--group">
                                            <span class="input-group-text mobile-code">
                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="<?php echo e(old('mobile')); ?>" class="form-control form--control checkUser" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('Address'); ?></label>
                                    <input type="text" class="form--control" name="address" value="<?php echo e(old('address')); ?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('State'); ?></label>
                                    <input type="text" class="form--control" name="state" value="<?php echo e(old('state')); ?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('Zip Code'); ?></label>
                                    <input type="text" class="form--control" name="zip" value="<?php echo e(old('zip')); ?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form--label"><?php echo app('translator')->get('City'); ?></label>
                                    <input type="text" class="form--control" name="city" value="<?php echo e(old('city')); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">
                                    <?php echo app('translator')->get('Submit'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php if(auth()->user()->login_by): ?>
    <?php $__env->startPush('script'); ?>
        <script>
            "use strict";
            (function($) {
                <?php if($mobileCode): ?>
                    $(`option[data-code=<?php echo e($mobileCode); ?>]`).attr('selected', '');
                <?php endif; ?>

                $('select[name=country]').change(function() {
                    $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                    $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                    $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                });
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            })(jQuery);
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make($activeTemplate.'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/user/user_data.blade.php ENDPATH**/ ?>