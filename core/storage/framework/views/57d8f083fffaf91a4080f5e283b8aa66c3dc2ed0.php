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

    <!-- Products page slider - set image & URL in Admin → Frontend → Manage Section → Products Page Slider -->
    <div class="m-b1">
        <div class="swiper-btn-center-lr">
            <div class="swiper-container tag-group mt-4 demo-swiper">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $productSliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $data = $slide->data_values;
                            $imgFile = isset($data->image) ? trim((string) $data->image) : '';
                            $useDefault = $imgFile !== '' && in_array($imgFile, productSliderDefaultImages(), true);
                            if ($useDefault) {
                                $imgSrc = url('') . '/assets/assets2/images/slider/' . $imgFile;
                            } else {
                                $imgSrc = getImage('assets/images/frontend/products_slider/' . $imgFile, '800x260');
                            }
                            $slideUrl = $data->url ?? '#';
                            $external = $slideUrl && (str_starts_with((string)$slideUrl, 'http') || str_starts_with((string)$slideUrl, '//'));
                        ?>
                        <div class="swiper-slide">
                            <a href="<?php echo e($slideUrl); ?>" <?php if($external): ?> target="_blank" rel="noopener" <?php endif; ?>>
                                <div class="card">
                                    <img src="<?php echo e($imgSrc); ?>" alt="<?php echo e($data->title ?? 'wallet-image'); ?>">
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent -->
    <div class="dashboard-body__content">

        <!-- welcome balance Content Start -->
        <div class="welcome-balance mt-2 mb-5">

            <div class="row">

                <div class="col-xl-9 col-sm-12 d-flex justify-content-start ">

                    <h4 class="mb-0"
                        style=" background: linear-gradient(270deg, #D0CCA1 9.16%, #DD553E 42.99%, #3219E3 87.83%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent;">
                        HI <?php echo e(Auth::user()->username ?? ""); ?>,</h4>

                </div>

                <div class="col-xl-3 col-sm-12 d-flex justify-content-end">
                    <select id="urlSelect" onchange="redirectToUrl()" class="btn btn-sm btn-dark">
                        <option value="">Categories</option>
                        <?php $__currentLoopData = $categoriesdrop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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


                <div class="col-12 mb-5 my-4">
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




                <div>
                    <h5 class="d-flex justify-content-start">Explore Product 👌</h5>
                </div>





                <div class="col-12">
                    <div id="category-wrapper">
                        <?php echo $__env->make($activeTemplate . 'partials.category_loop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="text-center my-3" id="loading" style="display: none;">
                        <p>Please wait Loading more products...</p>
                    </div>
                </div>

                <div class="text-center my-3" id="loading" style="display: none;">
                    <p>Please wait Loading more Products...</p>
                </div>

            </div>



        </div>











    </div>



    <div id="flash-buy-box" style="position: fixed; bottom: 80px; left: 10px; right: 10px; z-index: 9999; background: rgba(5,67,159,0.8); color: white; padding: 10px; border-radius: 10px; display: none; text-align: center;">
        <span id="flash-buy-text"></span>
    </div>


    <script>
        let nextPage = "<?php echo e($categories->nextPageUrl()); ?>";
        let loading = false;

        window.onscroll = function () {
            if (loading || !nextPage) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loading = true;
                document.getElementById('loading').style.display = 'block';

                fetch(nextPage, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const wrapper = document.getElementById('category-wrapper');
                        wrapper.insertAdjacentHTML('beforeend', data.html);
                        nextPage = data.next_page;
                        loading = false;
                        document.getElementById('loading').style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error loading more:', error);
                        loading = false;
                        document.getElementById('loading').style.display = 'none';
                    });
            }
        };
    </script>

    <script>
        const messages = [
            <?php $__currentLoopData = $bought; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                "<?php echo e(Str::limit($purchase->user_name, 4, '***')); ?> just bought <?php echo e(Str::limit($purchase->item, 16, '...')); ?> for ₦<?php echo e(number_format($purchase->amount)); ?>",
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ];

        let index = 0;
        const flashBox = document.getElementById('flash-buy-box');
        const flashText = document.getElementById('flash-buy-text');

        if (messages.length > 0) {
            flashBox.style.display = 'block';

            setInterval(() => {
                flashText.innerText = messages[index];
                flashBox.style.opacity = 1;

                setTimeout(() => {
                    flashBox.style.opacity = 0;
                }, 4000);

                index = (index + 1) % messages.length;
            }, 5000);
        }
    </script>





<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/products.blade.php ENDPATH**/ ?>