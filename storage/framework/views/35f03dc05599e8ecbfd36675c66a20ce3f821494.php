<?php $__env->startSection('title'); ?> Reporting <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Reporting Kegiatan Warga  <?php $__env->endSlot(); ?>
         <?php $__env->slot('title_li'); ?> Simonas   <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3"><strong>FILTER JUMLAH KEGIATAN</strong></div>
                    <form action="<?php echo e(route('super-kegiatan-reporting-filterDate')); ?>" method="GET">
                        <div style="background-color: rgba(200, 200, 255, 0.249)" class="row justify-content-center pt-3 pb-3">
                            <div class="col-md-3 col-6">
                                <label for="startDate">Tanggal Awal</label>
                                <input type="text" name="startDate" id="startDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="endDate">Tanggal Akhir</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-1 col-12 mt-2 align-self-end">
                                <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                            </div>
                        </div>
                    </form>
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
    <script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    
        $('#searchButton').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
    
            if (startDate && endDate) {
                filterTableByDateRange(startDate, endDate);
            } else {
                alert('Silahkan tanggal mulai dan tanggal akhirnya');
            }
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/reporting/index.blade.php ENDPATH**/ ?>