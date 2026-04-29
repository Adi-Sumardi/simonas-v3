

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Asrama YAPI</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/asrama/update/<?php echo e($asrama->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_asrama">Nama Asrama:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($asrama->nama_asrama); ?>" type="text" class="form-control" name="nama_asrama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_asrama" class="control-label">Alamat:</label><span
                            class="text-danger">
                            <input value="<?php echo e($asrama->alamat_asrama); ?>" type="text" class="form-control" name="alamat_asrama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun_jabatan">Tahun Jabatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($asrama->tahun_jabatan); ?>" type="date" class="form-control" name="tahun_didirikan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="direktur" class="control-label">Direktur Asrama:</label><span
                                class="text-danger">
                            <input value="<?php echo e($asrama->direktur); ?>" type="text" class="form-control" name="direktur">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ketua" class="control-label">Ketua Asrama:</label><span
                                class="text-danger">
                            <input value="<?php echo e($asrama->ketua); ?>" type="text" class="form-control" name="ketua">
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
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/edit_asrama.blade.php ENDPATH**/ ?>