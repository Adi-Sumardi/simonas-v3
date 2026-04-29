

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Alumni Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Alumni Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/alumni/update/<?php echo e($alumni->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Lengkap:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->nama_lengkap); ?>" type="text" class="form-control" name="nama_kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->email); ?>" type="text" class="form-control" name="email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp">Nomor Telphone:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->no_telp); ?>" type="text" class="form-control" name="no_telp">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama">Asrama:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->asrama); ?>" type="text" class="form-control" name="asrama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan">Angkatan:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->angkatan); ?>" type="text" class="form-control" name="angkatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gelar">Gelar:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->gelar); ?>" type="text" class="form-control" name="gelar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_asal">Alamat Asal:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->alamat_asal); ?>" type="text" class="form-control" name="alamat_asal">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_sekarang">Alamat Sekarang:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->alamat_sekarang); ?>" type="text" class="form-control" name="alamat_sekarang">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($alumni->pekerjaan); ?>" type="text" class="form-control" name="pekerjaan">
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/edit_alumni.blade.php ENDPATH**/ ?>