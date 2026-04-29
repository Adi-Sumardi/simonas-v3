<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">History Pengisian Karakter Islami Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-karakter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen Pengaderan</th>
                        <th>Waktu Pengisian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $karakters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karakter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($karakter->nama_warga); ?></td>
                        <td><?php echo e($karakter->komponen); ?></td>
                        <td><?php echo e($karakter->waktu); ?></td>
                        
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/history/karakter_id.blade.php ENDPATH**/ ?>