<!doctype html>
<html lang="<?php echo e(config('app.locale')); ?>" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> <?php echo e($general->siteName(__($pageTitle))); ?></title>

    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Same CSS as /products (layouts.main) -->
    <link rel="shortcut icon" href="<?php echo e(url('')); ?>/assets/assets2/images/logo/favicon.png">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/slick.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/main.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets2/css/swipper.min.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css">
    <link rel="stylesheet" href="<?php echo e(url('')); ?>/assets/assets/vendor/swiper/swiper-bundle.min.css">
    <?php echo $__env->yieldPushContent('style-lib'); ?>
    <?php echo $__env->yieldPushContent('style'); ?>
</head>

<body>
    <?php echo $__env->yieldPushContent('fbComment'); ?>

    <div class="preloader">
        <div class="loader-p"></div>
    </div>

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a href="#" class="scroll-top" aria-label="Scroll to top"><i class="las la-long-arrow-alt-up"></i></a>

    <?php echo $__env->yieldContent('app'); ?>

    <?php
        @$cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    ?>

    <?php if(@$cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie')): ?>
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">
                <?php echo e($cookie->data_values->short_desc); ?> <a href="<?php echo e(route('cookie.policy')); ?>" target="_blank"><?php echo app('translator')->get('learn more'); ?></a>
            </p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy"><?php echo app('translator')->get('Allow'); ?></a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Same JS as /products (layouts.main) -->
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/boostrap.bundle.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/countdown.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/counterup.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/slick.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/jquery.magnific-popup.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/apexchart.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/demo.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/js/dz.carousel.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/js/settings.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets/js/custom.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/main.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/js1.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/js2.js"></script>
    <script src="<?php echo e(url('')); ?>/assets/assets2/js/js3.js"></script>
    <?php echo $__env->yieldPushContent('script-lib'); ?>
    
    <?php echo $__env->yieldPushContent('script'); ?>

    <?php echo $__env->make('partials.plugins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('partials.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "<?php echo e(route('home')); ?>/change/" + $(this).val();
            });

            $('.policy').on('click', function() {
                $.get('<?php echo e(route('cookie.accept')); ?>', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);

                if(element.hasClass('exclude')){
                    return false;
                }

                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            // Scroll to top: show after scroll, click to scroll
            var $scrollTop = $('.scroll-top');
            if ($scrollTop.length) {
                $(window).on('scroll', function() {
                    if (window.pageYOffset > 300) $scrollTop.addClass('show'); else $scrollTop.removeClass('show');
                });
                $scrollTop.on('click', function(e) {
                    e.preventDefault();
                    $('html, body').animate({ scrollTop: 0 }, 400);
                });
            }
        })(jQuery);
    </script>
    <style>
    .scroll-top {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(99, 102, 241, .4);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: opacity .25s, visibility .25s, transform .2s;
    }
    .scroll-top:hover { color: #fff; transform: translateY(-2px); }
    .scroll-top.show { opacity: 1; visibility: visible; }
    </style>
</body>

</html>
<?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/layouts/app.blade.php ENDPATH**/ ?>