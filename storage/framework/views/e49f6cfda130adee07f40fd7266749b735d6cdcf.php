<?php $__env->startSection('title'); ?>
    List Mentor
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        List Mentor
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title_li'); ?>
        List Mentor
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6">
                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-md-2">
                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(url('/data_photo/' . $user->avatar)); ?>" alt="Card image" height="40" width="40">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <a href="/super-mentor-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                            <br>
                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                            <?php if($user->average_ip !== null): ?>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: <?php echo e(number_format($user->average_ip, 2)); ?>

                                                    </small>
                                                </p>
                                            <?php else: ?>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: Data tidak tersedia
                                                    </small>
                                                </p>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    Jumlah Data: <?php echo e($users->total()); ?>

                    <br/>
                    <br/>
                    <?php echo e($users->links()); ?>

                    <!-- end row -->

                    
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- plugin js -->
    <script src="<?php echo e(URL::asset('libs/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- jquery.vectormap map -->
    <script src="<?php echo e(URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js')); ?>"></script>

    <!-- Calendar init -->
    <script src="<?php echo e(URL::asset('js/pages/dashboard.init.js')); ?>"></script>

    <!-- Sweet Alerts js -->
    <script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

    <!-- Sweet alert init js-->
    <script src="<?php echo e(URL::asset('/js/pages/sweet-alerts.init.js')); ?>"></script>

    <!-- Required datatable js -->
    <script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>

    <!-- Datatable init js -->
    <script src="<?php echo e(URL::asset('/js/pages/datatables.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/mentor/index.blade.php ENDPATH**/ ?>