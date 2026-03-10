<?php $__env->startSection('content'); ?>
<div class="dhistory">
    <div class="dhistory__container">
        <div class="dhistory__head">
            <div>
                <h1 class="dhistory__title"><?php echo app('translator')->get('Payment History'); ?></h1>
                <p class="dhistory__sub"><?php echo app('translator')->get('View and track all your deposits and payments.'); ?></p>
            </div>
            <a href="<?php echo e(route('user.home')); ?>" class="dhistory__back"><i class="las la-arrow-left"></i> <?php echo app('translator')->get('Dashboard'); ?></a>
        </div>

        <div class="dhistory__toolbar">
            <form method="get" action="<?php echo e(request()->url()); ?>" class="dhistory__search">
                <input type="text" name="search" class="dhistory__search-input form-control" value="<?php echo e(request('search')); ?>" placeholder="<?php echo app('translator')->get('Search by Trx'); ?>">
                <button type="submit" class="dhistory__search-btn"><i class="las la-search"></i></button>
            </form>
        </div>

        <div class="dhistory__card">
            <div class="dhistory__table-wrap">
                <table class="dhistory__table">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('Trx'); ?></th>
                            <th><?php echo app('translator')->get('Time'); ?></th>
                            <th><?php echo app('translator')->get('Amount'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><code class="dhistory__trx"><?php echo e($deposit->trx); ?></code></td>
                            <td><span class="dhistory__muted"><?php echo e(diffForHumans($deposit->created_at)); ?></span></td>
                            <td><strong><?php echo e($general->cur_sym); ?><?php echo e(showAmount($deposit->amount)); ?></strong></td>
                            <td><?php echo $deposit->statusBadge; ?></td>
                            <td>
                                <?php if($deposit->status == 0): ?>
                                    <a href="<?php echo e(route('user.resolve.deposit')); ?>?trx=<?php echo e(urlencode($deposit->trx)); ?>" class="dhistory__resolve"><?php echo app('translator')->get('Resolve'); ?></a>
                                <?php else: ?>
                                    <span class="dhistory__muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="dhistory__empty">
                                <div class="dhistory__empty-inner">
                                    <i class="las la-file-invoice-dollar dhistory__empty-icon"></i>
                                    <p class="dhistory__empty-text"><?php echo e(__($emptyMessage ?? 'No payments yet')); ?></p>
                                    <a href="<?php echo e(route('user.deposit.new')); ?>" class="dhistory__empty-btn"><?php echo app('translator')->get('Fund Wallet'); ?></a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($deposits->hasPages()): ?>
                <div class="dhistory__pagination">
                    <?php echo e(paginateLinks($deposits)); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content dhistory__modal">
            <div class="modal-header">
                <h6 class="modal-title" id="detailModalTitle"><?php echo app('translator')->get('Details'); ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush userData"></ul>
                <div class="feedback"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<style>
.dhistory {
    padding: 1.5rem 0 3rem;
    min-height: 60vh;
}
.dhistory__container {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1rem;
}
@media (min-width: 768px) {
    .dhistory__container { padding: 0 1.5rem; }
}

.dhistory__head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.dhistory__title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}
.dhistory__sub {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0;
}
.dhistory__back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6366f1;
    text-decoration: none;
}
.dhistory__back:hover { color: #4f46e5; }

.dhistory__toolbar {
    margin-bottom: 1.25rem;
}
.dhistory__search {
    display: flex;
    max-width: 320px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.dhistory__search-input {
    flex: 1;
    border: none !important;
    padding: 0.65rem 1rem !important;
    font-size: 0.9375rem !important;
    min-width: 0;
}
.dhistory__search-input:focus {
    box-shadow: none !important;
    outline: none !important;
}
.dhistory__search-btn {
    padding: 0.65rem 1rem;
    border: none;
    background: #6366f1;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dhistory__search-btn:hover { background: #4f46e5; color: #fff; }

.dhistory__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,.04);
    box-shadow: 0 4px 24px rgba(0,0,0,.05);
    overflow: hidden;
}
.dhistory__table-wrap {
    overflow-x: auto;
}
.dhistory__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9375rem;
}
.dhistory__table th {
    text-align: left;
    padding: 14px 1.25rem;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.dhistory__table td {
    padding: 14px 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.dhistory__table tbody tr:hover {
    background: #fafafa;
}
.dhistory__table tbody tr:last-child td { border-bottom: none; }
.dhistory__trx {
    font-size: 0.8125rem;
    background: #f1f5f9;
    padding: 5px 10px;
    border-radius: 8px;
    color: #475569;
}
.dhistory__muted { color: #94a3b8; }
.dhistory__resolve {
    display: inline-block;
    padding: 6px 12px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #dc2626;
    background: rgba(220, 38, 38, .08);
    border-radius: 8px;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.dhistory__resolve:hover {
    background: rgba(220, 38, 38, .15);
    color: #b91c1c;
}

.dhistory__empty {
    padding: 3rem 1.5rem !important;
    vertical-align: middle;
}
.dhistory__empty-inner {
    text-align: center;
    max-width: 280px;
    margin: 0 auto;
}
.dhistory__empty-icon {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}
.dhistory__empty-text {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0 0 1.25rem;
}
.dhistory__empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.5rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 10px;
    text-decoration: none;
}
.dhistory__empty-btn:hover { color: #fff; opacity: .95; }

.dhistory__pagination {
    padding: 1rem 1.25rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.dhistory__pagination .pagination { margin: 0; gap: 4px; }
.dhistory__pagination .page-link {
    border-radius: 8px !important;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}

.dhistory__modal { border-radius: 16px; border: none; box-shadow: 0 24px 48px rgba(0,0,0,.12); }
.dhistory__modal .modal-header { border-bottom: 1px solid #f1f5f9; }
.dhistory__modal .modal-footer { border-top: 1px solid #f1f5f9; }

@media (max-width: 767.98px) {
    .dhistory__table th,
    .dhistory__table td { padding: 10px 0.75rem; font-size: 0.875rem; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
(function($) {
    "use strict";
    $('.detailBtn').on('click', function() {
        var modal = $('#detailModal');
        var userData = $(this).data('info');
        var html = '';
        if (userData) {
            userData.forEach(function(element) {
                if (element.type != 'file') {
                    html += '<li class="list-group-item d-flex justify-content-between align-items-center"><span>' + element.name + '</span><span>' + element.value + '</span></li>';
                }
            });
        }
        var adminFeedback = $(this).data('admin_feedback') != undefined
            ? '<div class="my-3 ms-2"><strong><?php echo e(__("Admin Feedback")); ?></strong><p>' + $(this).data('admin_feedback') + '</p></div>'
            : '';
        if (!html && !adminFeedback) html = '<span class="d-block text-center mt-2 mb-2"><?php echo e(__($emptyMessage ?? "Data not found")); ?></span>';
        modal.find('.userData').html(html);
        modal.find('.feedback').html(adminFeedback);
        modal.modal('show');
    });
})(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/programs/loggsplug/core/resources/views/templates/basic/user/deposit_history.blade.php ENDPATH**/ ?>