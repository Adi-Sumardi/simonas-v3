

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Alumni Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-alumni">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Asrama</th>
                        <th>Tahun Masuk</th>
                        <th>Tahun Keluar</th>
                        <th>Pekerjaan Saat ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><img src="<?php echo e(url ('/uploads/avatars/'.$user->avatar)); ?>" class="avatar img-circle" style="width:50px; height:50px;"></td>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->asrama); ?></td>
                        <td><?php echo e($user->tahun_masuk); ?></td>
                        <td><?php echo e($user->tahun_keluar); ?></td>
                        <td><?php echo e($user->pekerjaan); ?></td>
                        <td>                       
                                <a href="/mentor/alumni/detail/<?php echo e($user->id); ?>" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info"></i></a>
                        </td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/alumni/index.blade.php ENDPATH**/ ?>