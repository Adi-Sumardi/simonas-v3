<?php $__env->startSection('title'); ?>
Eksekutif Summary
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- DataTables -->
<link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Select2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('mahasiswa.common-components.breadcrumb'); ?>
<?php $__env->slot('title'); ?>
Data Summary Asrama
<?php $__env->endSlot(); ?>
<?php $__env->slot('li_1'); ?>
Pages
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="text-right mb-3">
            <button class="btn btn-secondary" onclick="printCard()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3"><strong class="font-size-18">Data Summary Asrama</strong></div>
                <div class="row justify-content-center pt-3 pb-3">
                    <div class="col-md-3 col-6">
                        <label>Dari Tanggal</label>
                        <input readonly type="text" class="form-control" value="<?php echo e($startDate); ?>">
                    </div>
                    <div class="col-md-3 col-6">
                        <label>Sampai Tanggal</label>
                        <input readonly type="text" class="form-control" value="<?php echo e($endDate); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <p>Asrama</p>
                                <p><strong> <?php echo e($asrama); ?> </strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <p>Jumlah Warga</p>
                                <p><strong> <?php echo e($jumlahWargaAsrama); ?> </strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <h5>#Kegiatan Warga</h5>
                <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama Warga</th>
                            <th>Akademik</th>
                            <th>Leadership</th>
                            <th>Karakter Islami</th>
                            <th>Kreatifitas & Kewirausahaan</th>
                            <th>Jumlah Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $table1Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($data['nama']); ?></td>
                            <td>
                                <span>
                                    <?php echo e($data['jumlahAkademik']); ?>

                                </span>
                            </td>

                            <td>
                                <span>
                                    <?php echo e($data['jumlahLeadership']); ?>

                                </span>
                            </td>

                            <td>
                                <span>
                                    <?php echo e($data['jumlahKarakter']); ?>

                            </td>

                            <td>
                                <span>
                                    <?php echo e($data['jumlahKreatif']); ?>

                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge text-white <?php echo e($data['jumlahKegiatan'] < 28 ? 'bg-danger' : 'bg-success'); ?>">
                                    <?php echo e($data['jumlahKegiatan']); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <br>
                <h5>#IPK Warga</h5>
                <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama Warga</th>
                            <th>IPK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $table2Data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($data['nama']); ?></td>
                            <td>
                                <span
                                    class="badge text-white <?php echo e($data['averageIP'] < 3 ? 'bg-danger' : 'bg-success'); ?>">
                                    <?php echo e(number_format($data['averageIP'], 2)); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- Required datatable js -->
<script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>

<!-- Datatable init js -->
<script src="<?php echo e(URL::asset('/js/pages/datatables.init.js')); ?>"></script>

<!-- apexcharts -->
<script src="<?php echo e(URL::asset('/libs/apexcharts/apexcharts.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/profile.init.js')); ?>"></script>

<!-- apexcharts init -->
<script src="<?php echo e(URL::asset('/js/pages/apexcharts.init.js')); ?>"></script>

<!-- Select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Sweet Alerts js -->
<script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

<!-- Sweet alert init js-->
<script src="<?php echo e(URL::asset('/js/pages/sweet-alerts.init.js')); ?>"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.js"></script>

<script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

<script>
    function printCard() {
            var printContents = document.querySelector('.card-body').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/reporting/summary.blade.php ENDPATH**/ ?>