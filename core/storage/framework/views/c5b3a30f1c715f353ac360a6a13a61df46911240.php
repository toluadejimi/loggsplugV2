<?php $__env->startSection('content'); ?>
    <div class="container py-4">

        
        <div class="row mb-4">
            <div class="col-lg-8 col-md-9">







                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-muted mb-1"><?php echo app('translator')->get('Product'); ?></h6>
                        <p class="mb-0"><?php echo e(optional(optional($order->orderItems->first())->product)->name ?? optional(\App\Models\Product::find($order->product_id))->name ?? 'N/A'); ?></p>
                        <p class="mb-0 mt-1"><span class="fw-bold text-muted"><?php echo app('translator')->get('Quantity'); ?>:</span> <?php echo e($order->orderItems->count() - 1); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-3 d-flex align-items-end justify-content-end my-3">
                <a href="/user/copy/<?php echo e($get_id); ?>" class="btn btn-primary btn-sm rounded-pill px-4">
                    <i style="color: white" class="fa fa-copy me-2"></i><?php echo app('translator')->get('Copy All'); ?>
                </a>
            </div>
        </div>

        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-4 text-center text-uppercase"><?php echo app('translator')->get('Latest Order History'); ?></h5>

                <?php if($orderItems->count()): ?>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th><?php echo app('translator')->get('Product Details'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Copy'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->productDetail): ?>
                                <tr>
                                    <td>
                                        <input type="text"
                                               readonly
                                               class="form-control border-0 bg-light small copy-input"
                                               value="<?php echo e(strip_tags($item->productDetail->details ?? '')); ?>"
                                               id="copyInput<?php echo e($item->productDetail->id); ?>">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-secondary btn-sm rounded-circle copy-btn"
                                                data-target="copyInput<?php echo e($item->productDetail->id); ?>">
                                            <i style="color: black" class="fa fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-muted small"><?php echo app('translator')->get('Item detail unavailable'); ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="60" class="mb-3" alt="No data">
                        <h6 class="fw-semibold"><?php echo app('translator')->get('No order data found'); ?></h6>
                        <p class="text-muted small"><?php echo app('translator')->get('You have no recent orders yet.'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.copy-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const inputId = this.getAttribute('data-target');
                    const input = document.getElementById(inputId);
                    input.select();
                    input.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    input.blur();

                    // Show toast message
                    const alert = document.createElement('div');
                    alert.textContent = "Copied!";
                    alert.className = "copy-toast";
                    document.body.appendChild(alert);
                    setTimeout(() => alert.remove(), 2000);
                });
            });
        });
    </script>

    
    <style>
        .copy-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: #fff;
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: fadeInOut 2s ease;
            z-index: 9999;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            10%, 90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(20px); }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate.'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/order_details.blade.php ENDPATH**/ ?>