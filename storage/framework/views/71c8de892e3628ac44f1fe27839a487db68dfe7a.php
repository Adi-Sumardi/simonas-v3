

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">History Penilaian Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/admin/history/akademik" class="btn btn-info"> History Akademik</a>
                <a href="/admin/history/leadership" class="btn btn-info"> History Leadership</a>
                <a href="/admin/history/karakter" class="btn btn-info"> History Karakter Islam</a>
                <a href="/admin/history/kreatif" class="btn btn-info"> History Kreatifitas dan Wirausaha</a>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
        
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-akademik">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen Pengaderan</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $akademiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akademik): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($akademik->nama_warga); ?></td>
                        <td><?php echo e($akademik->komponen); ?></td>
                        <td><?php echo e($akademik->nama_penilai); ?></td>
                        <td><?php echo e($akademik->nilai); ?></td>
                        
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/history_index.blade.php ENDPATH**/ ?>