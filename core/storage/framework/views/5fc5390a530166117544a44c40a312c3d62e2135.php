<?php $__env->startSection('content'); ?>

    <div class="dashboard-body__content orders-page">

        <div class="orders-page-header mb-4">
            <h1 class="orders-page-title mb-2">Order history</h1>
            <p class="text-muted small mb-0">View and manage your orders.</p>
        </div>

        <div class="orders-stats row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card orders-stat-card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <p class="orders-stat-label text-muted small mb-1">Total orders</p>
                        <p class="orders-stat-value fw-700 mb-0"><?php echo e($count_order ?? 0); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card orders-stat-card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <p class="orders-stat-label text-muted small mb-1">Total spent</p>
                        <p class="orders-stat-value fw-700 mb-0"><?php echo e($general->cur_sym ?? 'NGN'); ?> <?php echo e(number_format($order_sum ?? 0)); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card orders-table-card border-0 shadow-sm">
            <div class="card-header orders-table-header bg-transparent border-0 py-4">
                <h5 class="mb-0 fw-600 d-flex align-items-center gap-2">
                    <i class="las la-shopping-bag"></i>
                    Latest order history
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table orders-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-600">Order</th>
                                <th class="text-muted small fw-600">Product</th>
                                <th class="text-muted small fw-600">Qty</th>
                                <th class="text-muted small fw-600">Amount</th>
                                <th class="text-muted small fw-600">Date</th>
                                <th class="text-muted small fw-600 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $qty = @$order->orderItems->count();
                                    $productName = \App\Models\Product::where('id', $order->product_id)->first()->name ?? 'Product';
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('user.order.details', $order->id)); ?>" class="orders-link fw-600 text-decoration-none">
                                            #<?php echo e($order->id); ?>

                                            <span class="text-muted small fw-normal">View details</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('user.order.details', $order->id)); ?>" class="orders-link text-decoration-none">
                                            <?php echo e($productName); ?>

                                        </a>
                                    </td>
                                    <td class="small"><?php echo e($qty ? $qty - 1 : 0); ?></td>
                                    <td class="fw-600"><?php echo e($general->cur_sym ?? 'NGN'); ?> <?php echo e(showAmount($order->total_amount)); ?></td>
                                    <td class="small text-muted"><?php echo e(diffForHumans($order->created_at)); ?></td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-sm orders-buy-again-btn buy-again-btn"
                                                data-product-id="<?php echo e($order->product_id); ?>"
                                                data-product-name="<?php echo e($productName); ?>">
                                            Buy again
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="orders-empty text-center py-5">
                                        <i class="las la-shopping-cart text-muted mb-2" style="font-size: 2.5rem;"></i>
                                        <p class="text-muted small mb-2">No orders yet.</p>
                                        <a href="<?php echo e(route('products')); ?>" class="btn btn-primary btn-sm rounded-pill">Browse products</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($orders->hasPages()): ?>
                    <div class="orders-pagination p-3 border-top d-flex justify-content-center">
                        <?php echo e(paginateLinks($orders)); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="buyAgainModal" tabindex="-1" aria-labelledby="buyAgainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-600" id="buyAgainModalLabel">Buy again</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="buyAgainForm" action="<?php echo e(route('user.deposit.insert')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="buyAgainProductId" value="">
                    <input type="hidden" name="payment" value="wallet">
                    <input type="hidden" name="gateway" value="250">
                    <div class="modal-body">
                        <p class="text-muted small mb-3" id="buyAgainProductLabel">Enter quantity for this product.</p>
                        <label for="buyAgainQty" class="form-label">Quantity</label>
                        <input type="number" name="qty" id="buyAgainQty" class="form-control" value="1" min="1" max="100" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill">Place order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('buyAgainModal');
            if (!modal) return;
            var productIdInput = document.getElementById('buyAgainProductId');
            var productLabel = document.getElementById('buyAgainProductLabel');
            var qtyInput = document.getElementById('buyAgainQty');
            document.querySelectorAll('.buy-again-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var productId = this.getAttribute('data-product-id');
                    var productName = this.getAttribute('data-product-name');
                    if (productIdInput) productIdInput.value = productId || '';
                    if (productLabel) productLabel.textContent = 'Enter quantity for: ' + (productName || 'this product');
                    if (qtyInput) qtyInput.value = '1';
                    var bsModal = typeof bootstrap !== 'undefined' && bootstrap.Modal ? new bootstrap.Modal(modal) : null;
                    if (bsModal) bsModal.show();
                    else { modal.classList.add('show'); modal.style.display = 'block'; }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
.orders-page { padding-bottom: 2rem; }
.orders-page-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
.orders-stat-card { border-radius: 12px; }
.orders-stat-value { font-size: 1.25rem; color: #1e293b; }
.orders-table-card { border-radius: 12px; }
.orders-table-header { padding-left: 1.25rem; padding-right: 1.25rem; }
.orders-table thead { background: #f8fafc; }
.orders-table th {
    padding: 0.65rem 1rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.orders-table td { padding: 0.75rem 1rem; vertical-align: middle; }
.orders-link { color: #3219E3; }
.orders-link:hover { color: #0F0673; }
.orders-buy-again-btn {
    background: #1e293b;
    color: #fff;
    border: none;
    border-radius: 9999px;
    padding: 0.35rem 0.85rem;
    font-size: 0.8rem;
    font-weight: 600;
    transition: background 0.2s;
}
.orders-buy-again-btn:hover { background: #0f172a; color: #fff; }
.orders-empty { border-radius: 0 0 12px 12px; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/orders.blade.php ENDPATH**/ ?>