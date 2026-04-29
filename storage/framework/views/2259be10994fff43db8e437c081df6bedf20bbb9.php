<?php $__env->startSection('title'); ?>
    Daftar Kegiatan Asrama
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            Daftar Kegiatan Asrama
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title_li'); ?>
            Daftar Kegiatan Asrama
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo e(route('super-kegiatan-asrama-create')); ?>" type="button"
                            class="btn btn-outline-primary waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                        </a>
                    </div>
                    <br>

                    <h4 class="card-title">Daftar Kegiatan Asrama</h4>
                    <br>
                    <div style="overflow: scroll">
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Penyelenggara</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + $kegiatans->firstItem()); ?></td>
                                        <td><?php echo e($kegiatan->nama_kegiatan); ?></td>
                                        <td><?php echo e($kegiatan->penyelenggara); ?></td>
                                        <td><?php echo e(date('d-m-Y', strtotime($kegiatan->waktu))); ?></td>
                                        <td><?php echo e($kegiatan->tempat); ?></td>
                                        <td>
                                            <form action="/super-kegiatan-asrama-delete/<?php echo e($kegiatan->id); ?>"
                                                method="POST">
                                                <?php if ($userRole == 'super'): ?>
                                                    <a href="#" class="btn btn-info btn-action" data-toggle="tooltip" data-placement="top" title="Attendance">
                                                        <i class="fas fa-user-clock"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="/super-kegiatan-asrama-detail/<?php echo e($kegiatan->id); ?>"
                                                    class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail"><i
                                                        class="far fa-eye"></i></a>
                                                
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        Jumlah Data: <?php echo e($kegiatans->total()); ?>

                        <br/>
                        <br/>
                        <?php echo e($kegiatans->links()); ?>

                    </div>
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

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kegiatan/index.blade.php ENDPATH**/ ?>