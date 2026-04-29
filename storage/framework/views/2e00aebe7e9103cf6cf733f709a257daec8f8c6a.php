<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Akademik</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/penilaian/akademik/update/<?php echo e($akademik->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label><span
                                    class="text-danger">*</span>
                            <input value="<?php echo e($akademik->nama_warga); ?>" type="text" class="form-control" name="nama_warga">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen">Komponen Kurikulum Akademik:</label><span
                                class="text-danger">*</span>
                            <input value="<?php echo e($akademik->komponen); ?>" type="text" class="form-control" name="komponen">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                class="text-danger">*</span>
                            <input value="<?php echo e($akademik->kegiatan); ?>" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">*</span>
                            <input value="<?php echo e($akademik->waktu); ?>" type="text" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Nama Penilai:</label><span
                                class="text-danger">*</span>
                            <input value="<?php echo e($akademik->nama_penilai); ?>" type="text" class="form-control" name="nama_penilai">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Nilai:</label><span
                                    class="text-danger">*</span>
                            <input value="<?php echo e($akademik->nilai); ?>" type="text" class="form-control" name="nilai">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/akademik/penilaian_edit.blade.php ENDPATH**/ ?>