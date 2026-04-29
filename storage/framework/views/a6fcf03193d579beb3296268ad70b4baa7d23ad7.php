<?php $__env->startSection('title'); ?> Laporan SIMONAS <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> 
         Laporan
         
        <?php $__env->endSlot(); ?>
         <?php $__env->slot('title_li'); ?>  <button onclick="window.print()" class="btn btn-secondary mb-4">
            <i class="fa fa-print"></i> Print
        </button>  <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="badge badge-primary text-white text-center font-size-14">Jumlah Aspek Kegiatan</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Akademik</h5>
                                    </div>
                                    <h3><?php echo e($dt_akademiks); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Leadership</h5>
                                    </div>
                                    <h3><?php echo e($dt_leaderships); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Karakter Islami</h5>
                                    </div>
                                    <h3><?php echo e($dt_karakters); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Kreatifitas</h5>
                                    </div>
                                    <h3><?php echo e($dt_kreatifs); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <p class="badge badge-primary text-white text-center font-size-14">Kegiatan Terbaik Warga Asrama</p>
                    <div class="row">
                        <?php $__currentLoopData = $dt_kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="col-md-12 text-center">
                                        <img src="<?php echo e($item->avatar && file_exists(public_path('data_photo/' . $item->avatar)) 
                                                    ? url('/data_photo/' . $item->avatar) 
                                                    : url('/data_photo/default_photo.jpg')); ?>" 
                                             alt="Avatar" 
                                             class="rounded-circle avatar-lg">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <p class="badge badge-info mt-3"><?php echo e($item->name); ?></p>
                                        <p><?php echo e($item->asrama); ?></p>
                                        <h4><?php echo e($item->total_kegiatan); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <br>
                    <p class="badge badge-primary text-white text-center font-size-14">IPK Terbaik Warga Asrama</p>
                    <div class="row">
                        <?php $__currentLoopData = $dt_ipk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="col-md-12 text-center">
                                        <img src="<?php echo e($item->avatar && file_exists(public_path('data_photo/' . $item->avatar)) 
                                                    ? url('/data_photo/' . $item->avatar) 
                                                    : url('/data_photo/default_photo.jpg')); ?>" 
                                             alt="Avatar" 
                                             class="rounded-circle avatar-lg">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <p class="badge badge-info mt-3"><?php echo e($item->name); ?></p>
                                        <p><?php echo e($item->asrama); ?></p>
                                        <p><?php echo e($item->universitas); ?></p>
                                        <h4><?php echo e(number_format($item->rata_rata_ip, 2)); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- plugin js -->
    <script src="<?php echo e(URL::asset('libs/apexcharts/apexcharts.min.js')); ?>"></script>
    
    <!-- Calendar init -->
    <script src="<?php echo e(URL::asset('js/pages/dashboard.init.js')); ?>"></script>

    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('/libs/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- apexcharts init -->
    <script src="<?php echo e(URL::asset('/js/pages/apexcharts.init.js')); ?>"></script>

    <!-- Select2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/laporan/index.blade.php ENDPATH**/ ?>