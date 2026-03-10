<?php $__env->startSection('content'); ?>

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

    <!-- Recent -->
    <div class="m-b1">
        <div class="swiper-btn-center-lr">
            <div class="swiper-container tag-group mt-4 demo-swiper">
                <div class="swiper-wrapper">


                    <div class="swiper-slide">
                        <a href="https://Wa.me/+2347049631912">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slider6.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>


                    <div class="swiper-slide">
                        <a href="https://smslord.com/">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slider8.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>


                    <div class="swiper-slide">
                        <a href="https://socialplugboost.com/">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slider9.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>


                    <div class="swiper-slide">
                        <a href="/user/refer">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slider7.jpg"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>







                    <div class="swiper-slide">
                        <a href="https://chat.whatsapp.com/LaT8j2YC9KG8RH0iKAZlEj">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slide1.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>


                    <div class="swiper-slide">
                        <a href="https://t.me/Dmloggsplugdotcomdotcom">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slide2.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide ">
                        <a href="https://t.me/Dmloggsplugdotcom">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slide3.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slide4.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide">
                        <a href="/user/send-gift">
                            <div class="card">
                                <img src="<?php echo e(url('')); ?>/assets/assets2/images/slider/slider5.png"
                                     alt="wallet-image">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent -->
    <div class="dashboard-body__content">

        <!-- welcome balance Content Start -->
        <div class="welcome-balance mt-2 mb-5">

            <div class="row">

                <div class="col-9 d-flex justify-content-start">

                    <h4 class="mb-0"
                        style=" background: linear-gradient(270deg, #D0CCA1 9.16%, #DD553E 42.99%, #3219E3 87.83%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent;">
                        HI <?php echo e(Auth::user()->username ?? ""); ?>,</h4>

                </div>

                <div class="col-3 d-flex justify-content-end">
                    <select id="urlSelect" onchange="redirectToUrl()" class="btn btn-sm btn-dark">
                        <option value="">Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e(url('')); ?>/category-products/<?php echo e($data->name); ?>/<?php echo e($data->id); ?>"><?php echo e($data->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <script>
                        function redirectToUrl() {
                            var selectElement = document.getElementById("urlSelect");
                            var selectedUrl = selectElement.options[selectElement.selectedIndex].value;
                            if (selectedUrl !== "") {
                                window.location.href = selectedUrl;
                            }
                        }
                    </script>

                </div>

            </div>


        </div>
        <!-- welcome balance Content End -->

        <div class="dashboard-body__item-wrapper">

            <div class="">
                <div>
                    <h5 class="d-flex justify-content-start">Explore Product 👌</h5>
                </div>

                <div class="col-12">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $products = $category->products;
                        ?>
                        <div class="catalog-item-wrapper mb-2">
                            <div class="d-grid gap-2 mb-2">
                                <strong>
                                    <p style="font-size: 11px; background: linear-gradient(90deg, #020c49 0%, #4855a6 100%); border-radius:10px; color: white"
                                       class="p-2"><?php echo e(__($category->name)); ?></p>
                                </strong>
                            </div>

                            <div
                                style="font-size: 11px; border-radius: 10px; padding: 4px; background: #020000;color: #ffffff;">

                                <div class="row">
                                    <div class="col-2"></div>
                                    <div class="col-5">Product</div>
                                    <div class="col-3">Price</div>
                                    <div class="col-2">Stock</div>
                                </div>
                            </div>


                        </div>
                </div>


                <div class="col-12">

                    <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make($activeTemplate . 'partials/products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>


                <div class="col-12 d-flex justify-content-end mb-4">
                    <a style="background: linear-gradient(90deg, #020c49 0%, #4855a6 100%); border-radius:10px; color: white"
                       href="<?php echo e(route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id])); ?>"
                       class="btn btn-main btn-lg w-100 pill">
                        <?php echo app('translator')->get('View All'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                    </a>
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


        <div class="col-12 my-4">
            <?php if(auth()->guard()->check()): ?>

                <div class="card-title mt-3 text-center">
                    <h6 style="background: #565656; padding: 10px; border-radius: 10px; color: white"
                        class="text-left">RECENT ORDER</h6>
                </div>


                <div style="height:400px; width:100%; overflow-y: scroll;" class="card">
                    <div class="card-body">


                        <div class="dashboard-body__item">
                            <div class="table-responsive">
                                <table class="table style-two">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Time</th>

                                    </tr>

                                    <?php if($bought_qty == 0): ?>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $bought; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tr>
                                                <td><?php echo e(\Illuminate\Support\Str::limit($data->user_name,4, '.')); ?>, <span style="color: #f10054">just purchase</span><br/> <?php echo e(\Illuminate\Support\Str::limit($data->item,
                                    16, '...')); ?><span style="color: #000000">₦<?php echo e(number_format($data->amount)); ?></span></td>
                                                <td><?php echo e(diffForHumans($data->created_at)); ?></td>
                                            </tr>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>



                                    </thead>
                                </table>
                            </div>
                        </div>





                    </div>
                </div>
            <?php else: ?>

            <?php endif; ?>

        </div>



    </div>























    

    

    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    




    


    


    
    
    
    

    
    
    
    
    
    
    

    


    
    
    
    
    

    

    
    



    
    
    
    

    

    
    

    

    
    
    
    

    


    
    
    
    
    
    
    
    
    
    


    
    
    
    
    
    

    



    
    



    
    
    
    


    


    


    

    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    

    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    

    

    
    
    



    

    
    
    
    
    
    

    
    
    
    
    
    
    
    


    
    
    



    


    


    
    
    
    
    
    
    


    
    




    


    

    
    
    
    

    
    
    
    
    
    







    
    
    

    




    <!-- Page Content End-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/loggsplug/core/resources/views/templates/basic/products.blade.php ENDPATH**/ ?>