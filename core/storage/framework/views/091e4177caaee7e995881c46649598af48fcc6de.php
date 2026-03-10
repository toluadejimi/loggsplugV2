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

                    <div class="row card-title">

                        <h6>List of Gift Items</h6>

                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php $__empty_1 = true; $__currentLoopData = $gift_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>

                                    <td>
                                        <img src="<?php echo e($data->image); ?>" alt="image">

                                    </td>
                                    <td>
                                        <?php echo e($data->title); ?>

                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" href="/admin/delete-gift?id=<?php echo e($data->id); ?>">Delete</a>
                                        <a class="btn btn-dark btn-sm" data-toggle="modal"
                                           data-target="#exampleModal<?php echo e($data->id); ?>"
                                           href="/admin/update-gift?id=<?php echo e($data->id); ?>">Update</a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal<?php echo e($data->id); ?>" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Gift
                                                            Item</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="<?php echo e(route('admin.update.gift')); ?>"
                                                              method="POST" enctype="multipart/form-data">
                                                            <?php echo csrf_field(); ?>

                                                            <label>Gift Name</label>
                                                            <input type="text" class="form-control" name="title"
                                                                   required value="<?php echo e($data->title); ?>">


                                                            <label>Gift Image</label>
                                                            <input type="file" class="form-control" name="image"
                                                                   value="<?php echo e($data->image); ?>">

                                                            <input type="text" class="form-control" name="id"
                                                                   value="<?php echo e($data->id); ?>" hidden>


                                                            <button type="submit" class="btn btn-primary my-3">Save
                                                                changes
                                                            </button>

                                                        </form>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </td>


                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <?php endif; ?>

                            </tbody>

                            <?php echo e(paginateLinks($gift_items)); ?>



                        </table>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-lg-6 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">

                        <h6>New Gift Item</h6>

                    </div>

                    <form action="<?php echo e(route('admin.new.gift')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <label>Gift Name</label>
                        <input type="text" class="form-control" name="title" required>


                        <label class="my-2">Gift Image</label>
                        <input type="file" class="form-control" name="image">


                        <button type="submit" class="my-4 btn btn--primary w-100 btn-lg h-45"><?php echo app('translator')->get('Continue'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <hr>

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">

                        <h6>Gift Orders</h6>

                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Gift Items</th>
                                <th>QTY</th>
                                <th>Address</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $__empty_1 = true; $__currentLoopData = $gift_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>

                                    <td>
                                        <?php echo e($data->user->username); ?>

                                    </td>
                                    <td>
                                        <?php echo e($data->gift_item); ?>

                                    </td>
                                    <td>
                                        <?php echo e($data->qty); ?>

                                    </td>
                                    <td>
                                        <?php echo e($data->address); ?>

                                    </td>
                                    <td>
                                        <?php echo e($data->note); ?>

                                    </td>

                                    <td>
                                        <?php if($data->status == 0): ?>
                                            <a href="#" class="btn btn-warning">Pending</a>
                                        <?php elseif($data->status == 1): ?>
                                            <a href="#" class="btn btn-primary">Paid</a>
                                        <?php elseif($data->status == 2): ?>
                                            <a href="#" class="btn btn-primary">Shipped Out</a>
                                        <?php elseif($data->status == 3): ?>
                                            <a href="#" class="btn btn-primary">In Transit</a>
                                        <?php elseif($data->status == 4): ?>
                                            <a href="#" class="btn btn-success">Delivered</a>
                                        <?php else: ?>
                                            <a href="#" class="btn btn-success">Returned</a>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a class="btn btn-danger btn-sm"
                                           href="/admin/delete-gift-order?id=<?php echo e($data->id); ?>">Delete</a>
                                        <a class="btn btn-dark btn-sm" data-toggle="modal"
                                           data-target="#exampleModal<?php echo e($data->id); ?>"
                                           href="/admin/update-gift?id=<?php echo e($data->id); ?>">Update</a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal<?php echo e($data->id); ?>" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Gift
                                                            Item</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="<?php echo e(route('admin.update.order.gift')); ?>"
                                                              method="POST" enctype="multipart/form-data">
                                                            <?php echo csrf_field(); ?>

                                                            <label>Select Status</label>
                                                            <select class="form-control" name="status">
                                                                <option value="0">Pending</option>
                                                                <option value="1">Paid</option>
                                                                <option value="2">Shipped</option>
                                                                <option value="3">In-Transit</option>
                                                                <option value="4">Delivered</option>
                                                                <option value="5">Returned</option>
                                                            </select>
                                                            <input name="id" value="<?php echo e($data->id); ?>" hidden>


                                                            <button type="submit" class="btn btn-primary my-3">Save
                                                                changes
                                                            </button>

                                                        </form>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </td>


                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <?php endif; ?>

                            </tbody>

                            <?php echo e(paginateLinks($gift_orders)); ?>



                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>

<?php $__env->stopSection(); ?>






<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/loggsplug/core/resources/views/admin/gift.blade.php ENDPATH**/ ?>