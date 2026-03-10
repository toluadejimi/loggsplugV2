
<?php if($general->secure_password): ?>
    <?php $__env->startPush('script-lib'); ?>
        <script src="<?php echo e(asset('assets/global/js/secure_password.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title> LOGS PLUG</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(url('')); ?>/assets/assets2/images/logo/favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/fontawesome-all.min.css">
    <!-- Slick -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/slick.css">
    <!-- magnific popup -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/magnific-popup.css">
    <!-- line awesome -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/line-awesome.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/main.css">

</head>
<body>

<!--==================== Preloader Start ====================-->
<div class="loader-mask">
    <div class="loader">
        <div></div>
        <div></div>
    </div>
</div>
<!--==================== Preloader End ====================-->

<!--==================== Overlay Start ====================-->
<div class="overlay"></div>
<!--==================== Overlay End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->

<!-- ==================== Scroll to Top End Here ==================== -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
<!-- ==================== Scroll to Top End Here ==================== -->

<!-- ==================== Mobile Menu Start Here ==================== -->
<div class="mobile-menu d-lg-none d-block">
    <button type="button" class="close-button"> <i class="las la-times"></i> </button>
    <div class="mobile-menu__inner">
        <a href="/" class="mobile-menu__logo">
            <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/img.png" alt="Logo">
        </a>
        <div class="mobile-menu__menu">

            <ul class="nav-menu flx-align nav-menu--mobile">
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Home</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="index.html" class="nav-submenu__link"> Home One</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="index-two.html" class="nav-submenu__link"> Home Two</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Products</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="all-product.html" class="nav-submenu__link"> All Products</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="product-details.html" class="nav-submenu__link"> Product Details</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Pages</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="profile.html" class="nav-submenu__link"> Profile</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart.html" class="nav-submenu__link"> Shopping Cart</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-personal.html" class="nav-submenu__link"> Mailing Address</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-payment.html" class="nav-submenu__link"> Payment Method</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-thank-you.html" class="nav-submenu__link"> Preview Order</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="dashboard.html" class="nav-submenu__link"> Dashboard</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Blog</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="blog.html" class="nav-submenu__link"> Blog</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="blog-details.html" class="nav-submenu__link"> Blog Details</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="blog-details-sidebar.html" class="nav-submenu__link"> Blog Details Sidebar</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item">
                    <a href="contact.html" class="nav-menu__link">Contact</a>
                </li>
            </ul>
            <div class="header-right__inner d-lg-none my-3 gap-1 d-flex flx-align">

                <a href="register.html" class="btn btn-main pill">
        <span class="icon-left icon">
            <img src="<?php echo e(url('')); ?>/assets/assets2/images/icons/user.svg" alt="">
        </span>Create Account
                </a>
                <div class="language-select flx-align select-has-icon">
                    <img src="<?php echo e(url('')); ?>/assets/assets2/images/icons/globe.svg" alt="" class="globe-icon">
                    <select class="select py-0 ps-2 border-0 fw-500">
                        <option value="1">Eng</option>
                        <option value="2">Bn</option>
                        <option value="3">Eur</option>
                        <option value="4">Urd</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== Mobile Menu End Here ==================== -->

<!-- ================================== Account Page Start =========================== -->
<section class="account d-flex">
    <img src="<?php echo e(url('')); ?>/assets/assets2/images/thumbs/account-img.png" alt="" class="account__img">
    <div class="account__left d-md-flex d-none flx-align section-bg position-relative z-index-1 overflow-hidden">
        <img src="<?php echo e(url('')); ?>/assets/assets2/images/shapes/pattern-curve-seven.png" alt="" class="position-absolute end-0 top-0 z-index--1 h-100">
        <div class="account-thumb">
            <img src="<?php echo e(url('')); ?>/assets/assets2/images/thumbs/barrr.png" alt="">
            <div class="statistics animation bg-main text-center">
                <h5 class="statistics__amount text-white">LOGS</h5>
                <span class="statistics__text text-white font-14">PLUG</span>
            </div>
        </div>
    </div>
    <div class="account__right padding-y-120 flx-align">
        <div class="account-content">
            <a href="/" class="logo mb-64">
                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/img.png" alt="Logo">
            </a>
            <h4 class="account-content__title mb-48 text-capitalize">Reset Password</h4>


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
                            <button type="submit" class="btn btn-main btn-lg w-100 pill p-3"><?php echo app('translator')->get('Reset'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- ================================== Account Page End =========================== -->

<!-- Jquery js -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle Js -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/boostrap.bundle.min.js"></script>
<!-- CountDown -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/countdown.js"></script>
<!-- counter up -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/counterup.min.js"></script>
<!-- Slick js -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/slick.min.js"></script>
<!-- magnific popup -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/jquery.magnific-popup.js"></script>
<!-- apex chart -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/apexchart.js"></script>

<!-- main js -->
<script src="<?php echo e(url('')); ?>/assets/assets2/js/main.js"></script>

</body>
</html>

<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/loggsplug/core/resources/views/templates/basic/user/auth/passwords/reset.blade.php ENDPATH**/ ?>