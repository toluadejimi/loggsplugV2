<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone"/>
    <meta name="keywords" content=""/>
    <meta name="robots" content=""/>
    <meta name="description" content="Foodia - Food Restaurant Mobile App Template ( Bootstrap 5 + PWA )"/>
    <meta property="og:title" content="Foodia - Food Restaurant Mobile App Template ( Bootstrap 5 + PWA )"/>
    <meta property="og:description" content="Foodia - Food Restaurant Mobile App Template ( Bootstrap 5 + PWA )"/>
    <meta property="og:image" content="https://makaanlelo.com/tf_products_007/foodia/xhtml/social-image.png"/>
    <meta name="format-detection" content="telephone=no">

    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(url('')); ?>/assets/assets/images/favicon.png"/>

    <!-- Title -->
    <title>ACELOGS MARKET PLACE</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(url('')); ?>/assets/assets/css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Roboto+Slab:wght@100;300;500;600;800&display=swap"
        rel="stylesheet">

</head>
<body class="bg-white">
<div class="page-wraper">

    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader end-->

    <!-- Page Content -->
    <div class="page-content">

        <!-- Banner -->
        <div class="banner-wrapper">

            <div class="container inner-wrapper">
                <img src="<?php echo e(url('')); ?>/assets/assets/images/logo.svg" width="300" height="250">
                <p class="mb-0">Reset Password</p>
            </div>


        </div>
        <!-- Banner End -->
        <div class="account-box">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
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
                            <div class="alert alert-danger">
                                <?php echo e(session()->get('error')); ?>

                            </div>
                        <?php endif; ?>
                        <div class="mb-4">
                            <p><?php echo app('translator')->get('To recover your account please provide your email or username to find your account.'); ?></p>
                        </div>
                        <form method="POST" action="<?php echo e(route('user.password.email')); ?>" class="verify-gcaptcha">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label class="my-3"><?php echo app('translator')->get('Email or Username'); ?></label>
                                <input type="text" class="form-control" name="value" value="<?php echo e(old('value')); ?>"
                                       required autofocus="off">
                            </div>

                            <?php if (isset($component)) { $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243 = $component; } ?>
<?php $component = App\View\Components\Captcha::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('captcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Captcha::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243)): ?>
<?php $component = $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243; ?>
<?php unset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243); ?>
<?php endif; ?>

                            <div class="my-3">
                                <button type="submit" class="btn btn-block" style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color: white;"><?php echo app('translator')->get('Submit'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content End -->

<!--**********************************
    Scripts
***********************************-->
<script src="<?php echo e(url('')); ?>/assets/assets/js/jquery.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets/js/settings.js"></script>
<script src="<?php echo e(url('')); ?>/assets/assets/js/custom.js"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/user/auth/passwords/email.blade.php ENDPATH**/ ?>