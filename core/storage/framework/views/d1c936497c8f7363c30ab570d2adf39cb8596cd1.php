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

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="card custom--card">
                        <div class="card-body">
                            <div class="mb-4">
                                <p><?php echo app('translator')->get('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.'); ?></p>
                            </div>
                            <form method="POST" action="<?php echo e(route('user.password.update')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email" value="<?php echo e($email); ?>">
                                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                <div class="form-group">
                                    <label class="my-2"><?php echo app('translator')->get('Password'); ?></label>
                                    <div class="form-group">
                                        <input type="password"
                                               class="form-control <?php if($general->secure_password): ?> secure-password <?php endif; ?>"
                                               name="password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="my-2"><?php echo app('translator')->get('Confirm Password'); ?></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block" style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color: white;"><?php echo app('translator')->get('Submit'); ?></button>
                                </div>
                            </form>
                        </div>
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
<?php if($general->secure_password): ?>
    <?php $__env->startPush('script-lib'); ?>
        <script src="<?php echo e(asset('assets/global/js/secure_password.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/user/auth/passwords/reset.blade.php ENDPATH**/ ?>