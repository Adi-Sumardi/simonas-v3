<?php $__env->startSection('title'); ?>
    Detail Kegiatan Akademik
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        Detail Kegiatan Akademik
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title_li'); ?>
        Detail Kegiatan Akademik
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="<?php echo e(URL::previous()); ?>" type="button"
                            class="btn btn-outline-secondary waves-effect waves-light">
                            <i class="mdi mdi mdi-arrow-left font-size-16 align-middle mr-2"></i> Kembali
                        </a>
                    </div>

                    <div class="container px-2 py-4" id="custom-cards">    
                        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch ">
                          <div class="col">
                            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                              <div class="d-flex flex-column h-100 p-5 pb-3 text-black text-shadow-1">
                                <h4>Detail Kegiatan Akademik</h4>
                                <br>
                                <address>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Nama Warga</td>
                                                <td> :</td>
                                                <td><?php echo e($akademik->nama_warga); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Asrama</td>
                                                <td> :</td>
                                                <td><?php echo e($akademik->asrama); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Komponen</td>
                                                <td> :</td>
                                                <td> <strong> <?php echo e($akademik->komponen); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Kegiatan</td>
                                                <td> :</td>
                                                <td> <strong> <?php echo e($akademik->kegiatan); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Waktu</td>
                                                <td> :</td>
                                                <td> <strong> <?php echo e($akademik->waktu); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tempat Kegiatan</td>
                                                <td> :</td>
                                                <td> <strong> <?php echo e($akademik->tempat); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Uraian Kegiatan</td>
                                                <td> :</td>
                                                <td> <strong> <?php echo e($akademik->keterangan); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </address>
                                </div>
                            </div>
                          </div>
                    
                          <div class="col">
                            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                              <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                <h4>Foto Kegiatan</h4>
                                <br>
                                <address>
                                    <img src="<?php echo e(url('/data_file_akademik/' . $akademik->file)); ?>" class="img-thumbnail" style="height:auto; width:100%; vertical-align:middle; max-width:250px;" alt="">
                                </address>
                              </div>
                            </div>
                          </div>
                        </div>
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

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/akademik/detail.blade.php ENDPATH**/ ?>