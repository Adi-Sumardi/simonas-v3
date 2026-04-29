

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Alumni Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Detail Data Alumni Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/alumni/detail/<?php echo e($alumni->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label>
                                <p><?php echo e($alumni->nama_lengkap); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label>
                                <p><?php echo e($alumni->email); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp">Nomor Telphone:</label>
                                <p><?php echo e($alumni->no_telp); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama">Asrama:</label>
                                <p><?php echo e($alumni->asrama); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan">Angkatan:</label>
                                <p><?php echo e($alumni->angkatan); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gelar">Gelar:</label>
                                <p><?php echo e($alumni->gelar); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_asal">Alamat Asal:</label>
                                <p><?php echo e($alumni->alamat_asal); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_sekarang">Alamat Sekarang:</label>
                                <p><?php echo e($alumni->alamat_sekarang); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan:</label>
                                <p><?php echo e($alumni->pekerjaan); ?></p>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/detail_alumni.blade.php ENDPATH**/ ?>