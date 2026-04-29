<?php $__env->startSection('title'); ?> Reporting <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Reporting Kegiatan Warga  <?php $__env->endSlot(); ?>
         <?php $__env->slot('title_li'); ?> Simonas   <?php $__env->endSlot(); ?>
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
                    <div class="text-center mb-3"><strong>REKAP JUMLAH KEGIATAN WARGA ASRAMA</strong></div>
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
    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Asrama</th>
                                <th>Akademik</th>
                                <th>Leadership</th>
                                <th>Karakter Islami</th>
                                <th>Kreatifitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $groupedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asrama => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="6" class="font-weight-bold"><?php echo e($asrama); ?></td>
                                </tr>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['nama']); ?></td>
                                        <td><?php echo e($item['asrama']); ?></td>
                                        <td><?php echo e($item['jumlahAkademik']); ?></td>
                                        <td><?php echo e($item['jumlahLeadership']); ?></td>
                                        <td><?php echo e($item['jumlahKarakter']); ?></td>
                                        <td><?php echo e($item['jumlahKreatif']); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- plugin js -->
    <script src="<?php echo e(URL::asset('libs/apexcharts/apexcharts.min.js')); ?>"></script>
    
    <!-- jquery.vectormap map -->
    <script src="<?php echo e(URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js')); ?>"></script>
    
    <!-- Calendar init -->
    <script src="<?php echo e(URL::asset('js/pages/dashboard.init.js')); ?>"></script>

    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('/libs/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- apexcharts init -->
    <script src="<?php echo e(URL::asset('/js/pages/apexcharts.init.js')); ?>"></script>

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
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/reporting/filterDate.blade.php ENDPATH**/ ?>