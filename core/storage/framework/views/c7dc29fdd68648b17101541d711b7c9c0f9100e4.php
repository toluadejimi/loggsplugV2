<?php $__env->startSection('content'); ?>


    <!-- Banner -->



    <?php if(auth()->guard()->check()): ?>

        <div class="author-notification">

            <div class="p-2">

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




            </div>


            <div class="container inner-wrapper">


                <div class="dz-info">
                    <span class="text-dark"><?php echo e($greetings); ?></span>
                    <h3 class="name mb-0"><?php echo e(Auth::user()->username); ?> 👋</h3>
                </div>

                <div class="dz-info">
                    <img src="<?php echo e(url('')); ?>/assets/assets/images/wallet.svg" alt="wallet-image" width="30"
                        height="30">
                    <strong class="text-dark">NGN<?php echo e(number_format(Auth::user()->balance)); ?></strong><br>
                    <a href="/user/deposit/new" class="position-relative me-2 btn btn-block btn-dark">
                        Fund Wallet
                    </a>

                </div>


                <div class="offcanvas offcanvas-bottom rounded-0" tabindex="-1" id="offcanvasBottom2">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="offcanvas-body container fixed">

                        <div class="text-center mt-5">

                            <img src="<?php echo e(url('')); ?>/assets/assets/images/wall.svg" alt="wallet-image" width="70"
                                height="70">



                            <div class="my-3">
                                <h6>Fund Wallet</h6>
                                <p>Top up your funds in your wallet</p>
                            </div>

                        </div>

                        <form class="mt-3" action="/user/deposit/insert" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="form-group col-md-12">

                                <div class="mb-3 input-group">
                                    <span class="input-group-text"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter Amount to fund">
                                </div>

                            </div>


                            <div class="form-group col-md-12">
                                <label class="form--label"><?php echo app('translator')->get('Select Gateway'); ?></label>
                                <select class="form-control form-select" name="gateway" required>
                                    <option value=""><?php echo app('translator')->get('Select One'); ?></option>
                                    <?php $__currentLoopData = $gateway_currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($data->method_code); ?>" data-gateway="<?php echo e($data); ?>">
                                            <?php echo e($data->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>


                            <div class="view-title">
                                <div class="container">
                                    <button type="submit"
                                        class="btn btn-primary btn-rounded btn-block flex-1 ms-2">CONFIRM</button>
                                </div>
                            </div>

                        </form>



                    </div>
                </div>



            </div>
        </div>
    <?php else: ?>
        <div class="my-5">


        </div>


    <?php endif; ?>


    <!-- Banner End -->

    <!-- Page Content -->
    <div class="page-content">

        <div class="content-inner pt-0">
            <div class="container fb">
                <!-- Search -->














                <!-- Dashboard Area -->
                <div class="dashboard-area">

                    <!-- Categorie -->
                    <div class="swiper-btn-center-lr">
                        <div class="swiper-container mt-4 categorie-swiper">
                            <div class="swiper-wrapper">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="swiper-slide">
                                        <a href="/category-products/<?php echo e($data->name); ?>/<?php echo e($data->id); ?>" class="categore-box style-1">
                                            <?php if($data->image == null): ?>
                                                <div class="icon-bx" style="background-color: #ededed">
                                                    <img src="<?php echo e(url('')); ?>/assets/assets/images/fav.svg"
                                                         alt="wallet-image" width="30" height="30">
                                                </div>
                                                <span class="title text-dark text-small"><?php echo e(Str::upper($data->name)); ?></span>
                                            <?php else: ?>
                                                <div class="icon-bx" style="background-color: #ededed">
                                                    <img src="<?php echo e(url('')); ?>/assets/assets/images/<?php echo e($data->image); ?>"
                                                         alt="wallet-image" width="30" height="30">
                                                </div>
                                                <span class="title text-dark text-small"><?php echo e(Str::upper($data->name)); ?></span>
                                            <?php endif; ?>

                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>
                    <!-- Categorie End -->

                    <!-- Recent -->
                    <div class="m-b1">
                        <div class="swiper-btn-center-lr">
                            <div class="swiper-container tag-group mt-4 demo-swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="#">
                                        <div class="card">
                                            <img src="<?php echo e(url('')); ?>/assets/assets/images/slider/slide_1.png"
                                                alt="wallet-image">
                                        </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide active">
                                        <a href="https://chat.whatsapp.com/CQtiNorfsys3irydIog6ON">
                                        <div class="card">
                                            <img src="<?php echo e(url('')); ?>/assets/assets/images/slider/slide_2.png"
                                                alt="wallet-image">
                                        </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide ">
                                        <a href="https://t.me/ACELOGSTORE01">
                                        <div class="card">
                                            <img src="<?php echo e(url('')); ?>/assets/assets/images/slider/slide_3.png"
                                                alt="wallet-image">
                                        </div>
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="https://t.me/acelogstoreteam">
                                        <div class="card">
                                            <img src="<?php echo e(url('')); ?>/assets/assets/images/slider/slide_4.png"
                                                alt="wallet-image">
                                        </div>
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent -->

                    <!-- Recomended Start -->
                    <div class="title-bar">
                        <h5 class="title">Explore Product 👌</h5>
                    </div>

                    <div class="row mt-2">

                        <div class="col-xxl-10 col-xl-11">
                            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $products = $category->products;
                                ?>



                                <div class="catalog-item-wrapper mb-2">

                                    <div class="d-grid gap-2 mb-2">
                                        <strong>
                                            <p style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); border-radius:10px; color: white"
                                                class="p-2"><?php echo e(__($category->name)); ?></p>
                                        </strong>
                                    </div>

                                    <div style="font-size: 11px; border-radius: 100px; background: #10113D;color: #ffffff;">
                                        <div class="row">
                                            <div class="col-2"></div>
                                            <div class="col-3">Product</div>
                                            <div class="col-5">Price</div>
                                            <div class="col-2">Stock</div>
                                        </div>
                                    </div>


                                            <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo $__env->make($activeTemplate . 'partials/products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                                        </tbody>


                                    </table>


                                    <div class="col-12 d-flex justify-content-end mb-4" >
                                        <a style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color: #ffffff" href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
                                            class="btn  btn-sm">
                                            <?php echo app('translator')->get('View All'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                            </svg>


                                        </a>
                                    </div>




                                </div>


                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <div class="empty-data text-center">
                                    <div class="thumb">
                                        <img src="<?php echo e(asset($activeTemplateTrue . 'images/not-found.png')); ?>">
                                    </div>

                                    <h4 class="title"><?php echo app('translator')->get('No result found for "' . request()->search . '"'); ?></h4>
                                </div>
                            <?php endif; ?>
                            <?php echo e(paginateLinks($categories)); ?>

                        </div>
                    </div>







                </div>
            </div>
        </div>

    </div>




    <!-- Page Content End-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/products.blade.php ENDPATH**/ ?>