<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/kegiatan-update/<?php echo e($kegiatan->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Kegiatan:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($kegiatan->nama_kegiatan); ?>" type="text" class="form-control" name="nama_kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tujuan">Tujuan Kegiatan:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($kegiatan->tujuan); ?>" type="text" class="form-control" name="nama_kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="penyelenggara">Penyelenggara:</label><span
                                    class="text-danger">
                                <select name="penyelenggara" class="form-control <?php echo e($errors->has('penyelenggara') ? ' is-invalid': ''); ?>">
                                    <option value="YAPI">YAPI</option>
                                    <option value="Direktorat Keasramaan">Direktorat Keasramaan</option>
                                    <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Jati</option>
                                    <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                    <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                    <option value="Asrama Putri">Asrama Putri</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kegiatan">Jenis Kegiatan:</label><span
                                class="text-danger">
                                <select name="jenis_kegiatan" class="form-control <?php echo e($errors->has('jenis_kegiatan') ? ' is-invalid': ''); ?>">
                                    <option value="Program Kerja">Program Kerja</option>
                                    <option value="Non-Program Kerja">Non-Program Kerja</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($kegiatan->waktu); ?>" type="text" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Tempat Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($kegiatan->tempat); ?>" type="text" class="form-control" name="tempat">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                            <textarea value="<?php echo e($kegiatan->keterangan); ?>" type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="file">Lampiran File:</label>
                            <p><img width="300" src="<?php echo e(url ('/data_file/'.$kegiatan->file)); ?>"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file"
                                   class="control-label">Lampiran File Baru:</label><span
                                    class="text-danger">
                                <input id="file" type="file" placeholder="Keterangan Kegiatan"
                                        class="form-control<?php echo e($errors->has('file') ? ' is-invalid': ''); ?>"
                                        name="file" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('file')); ?>

                            </div>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kegiatan/edit.blade.php ENDPATH**/ ?>