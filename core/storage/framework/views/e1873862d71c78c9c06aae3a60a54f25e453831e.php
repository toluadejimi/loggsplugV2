<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo d-lg-none d-block" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(getImage(getFilePath('logoIcon') . '/dark_logo.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
            </a>
            

            

            
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="pas pa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                    </li>

                    <?php
                        $pages = App\Models\Page::where('tempname', $activeTemplate)
                            ->where('is_default', Status::NO)
                            ->get();
                    ?>

                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?php echo e(route('pages', [$data->slug])); ?>"><?php echo e(__($data->name)); ?></a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <li class="nav-item d-lg-none d-flex justify-content-between align-items-end">
                        <div class="accounts-buttons d-flex align-items-center">
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('user.logout')); ?>" class="accounts-buttons__link">
                                    <span class="icon fs-14 me-1"><i class="fas fa-sign-out-alt"></i></span><?php echo app('translator')->get('Logout'); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('user.login')); ?>" class="accounts-buttons__link">
                                    <span class="icon fs-14 me-1"><i class="fas fa-sign-in-alt"></i></span> <?php echo app('translator')->get('Login'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('user.home')); ?>" class="btn btn--base btn--sm">
                                    <span class="icon fs-14 me-1"><i class="fas fa-home"></i></span> <?php echo app('translator')->get('Dashboard'); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('user.register')); ?>" class="btn btn--base btn--sm">
                                    <span class="icon fs-14 me-1"><i class="fas fa-user-plus"></i></span> <?php echo app('translator')->get('Register'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if($general->multi_language): ?>
                            <?php
                                $language = App\Models\Language::all();
                            ?>
                            <div class="language-box">
                                <select class="langSel form-control form-select">
                                    <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->code); ?>" <?php if(session('lang') == $item->code): ?> selected <?php endif; ?>><?php echo e(__($item->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<?php $__env->startPush('style'); ?>
    <style>
        @media screen and (max-width:992px) {
            .navbar {
                gap: 20px;
            }

            .navbar-brand {
                flex: 1 1 calc(77% - 40px);
            }

            .search-box {
                order: 4;
                width: auto !important;
                max-width: unset !important;
                flex: 1 1 50%;
            }

            .category-nav {
                width: 26%;
                order: 3;
                min-width: max-content;
                text-align: left;
            }
        }

        .navbar .search-box {
            max-width: 100%;
            position: relative;
            top: 100%;
            visibility: visible;
            opacity: 1;
            transition: all ease 250ms;
            z-index: 9;
            margin-top: 0;
        }

        @media screen and (max-width: 470px) {
            .navbar {
                gap: 15px;
                column-gap: 10px;
            }
        }

        @media screen and (max-width: 424px) {
            .dropdown--menu {
                width: 180px;
                left: 0;
                right: 0;
            }
        }

        @media screen and (max-width: 374px) {
            .navbar-brand {
                flex: 1 1 calc(77% - 0px);
            }

            .search-text {
                display: none;
            }

            .category-nav {
                width: 10%;
            }

            .category-nav__button .icon {
                margin-right: 0 !important;
            }

            .category-nav .arrow {
                display: none;
            }
        }
    </style>
<?php $__env->stopPush(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/partials/header_bottom.blade.php ENDPATH**/ ?>