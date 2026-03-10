<?php $__env->startSection('panel'); ?>

    <div class="row mb-none-30">


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
            <div class="alert alert-success my-3">
                <?php echo e(session()->get('message')); ?>

            </div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger mt-2">
                <?php echo e(session()->get('error')); ?>

            </div>
        <?php endif; ?>


            <div class="col-lg-6 col-md-9 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">List of Payment Gateways</h6>

                        <?php $__currentLoopData = $gateway; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span class="fw-semibold"><?php echo e($data->name); ?></span>

                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input gateway-toggle"
                                        type="checkbox"
                                        role="switch"
                                        id="gateway-<?php echo e($data->id); ?>"
                                        data-id="<?php echo e($data->id); ?>"
                                        <?php echo e($data->status ? 'checked' : ''); ?>

                                    >
                                    <label class="form-check-label ms-2" for="gateway-<?php echo e($data->id); ?>">
                            <span id="label-<?php echo e($data->id); ?>">
                                <?php echo e($data->status ? 'Active' : 'Inactive'); ?>

                            </span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('.gateway-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        let id = this.dataset.id;
                        let isActive = this.checked ? 1 : 0;

                        fetch(`payment-gateway/${id}/toggle`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ status: isActive })
                        })
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById(`label-${id}`).innerText = data.status ? "Active" : "Inactive";
                            });
                    });
                });
            </script>



    </div>


    <script>
        document.getElementById('status-toggle').addEventListener('change', function() {
            let isActive = this.checked ? 1 : 0;

            fetch("/", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ status: isActive })
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('status-label').innerText = data.status ? "Active" : "Inactive";
                });
        });
    </script>

<?php $__env->stopSection(); ?>






<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/loggsplu/public_html/core/resources/views/admin/payment/index.blade.php ENDPATH**/ ?>