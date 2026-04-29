

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Alumni Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Alumni Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/alumni/store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label><span
                                    class="text-danger">*</span>
                            <input id="nama_lengkap" type="text"
                                   class="form-control<?php echo e($errors->has('nama_lengkap') ? ' is-invalid' : ''); ?>"
                                   name="nama_lengkap"
                                   tabindex="1" placeholder="Masukan nama lengkap" value="<?php echo e(old('nama_lengkap')); ?>"
                                   autofocus required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('nama_lengkap')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label><span
                                    class="text-danger">
                            <input id="email" type="text"
                                   class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"
                                   placeholder="Masukan email" name="email" tabindex="1"
                                   value="<?php echo e(old('email')); ?>"
                                   required autofocus>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('email')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp" class="control-label">Nomor Telphone:</label><span
                                    class="text-danger">*</span>
                            <input id="no_telp" type="text"
                                   class="form-control<?php echo e($errors->has('no_telp') ? ' is-invalid': ''); ?>"
                                   placeholder="Masukan Nomor Telphone" name="no_telp" tabindex="2" required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('no_telp')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama"
                                   class="control-label">Asrama:</label><span
                                    class="text-danger">*</span>
                            <input id="asrama" type="text" placeholder="Masukan Nama Asrama"
                                   class="form-control<?php echo e($errors->has('asrama') ? ' is-invalid': ''); ?>"
                                   name="asrama" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('asrama')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan"
                                   class="control-label">Angkatan:</label><span
                                    class="text-danger">*</span>
                                <input id="angkatan" type="text" placeholder="Angkatan asrama"
                                        class="form-control<?php echo e($errors->has('angkatan') ? ' is-invalid': ''); ?>"
                                        name="angkatan" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('angkatan')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gelar"
                                   class="control-label">Gelar:</label><span
                                    class="text-danger">
                                <input id="gelar" type="text" placeholder="Gelar pendidikan"
                                        class="form-control<?php echo e($errors->has('gelar') ? ' is-invalid': ''); ?>"
                                        name="gelar" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('gelar')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_asal"
                                   class="control-label">Alamat Asal:</label><span
                                    class="text-danger">
                                <textarea id="alamat_asal" type="text" placeholder="Alamat asal"
                                        class="form-control<?php echo e($errors->has('alamat_asal') ? ' is-invalid': ''); ?>"
                                        name="alamat_asal" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('alamat_asal')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_sekarang"
                                   class="control-label">Alamat Sekarang:</label><span
                                    class="text-danger">*</span>
                                <textarea id="alamat_sekarang" type="text" placeholder="Alamat Sekarang"
                                        class="form-control<?php echo e($errors->has('alamat_sekarang') ? ' is-invalid': ''); ?>"
                                        name="alamat_sekarang" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('alamat_sekarang')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan"
                                   class="control-label">Pekerjaan:</label><span
                                    class="text-danger">
                                <input id="pekerjaan" type="text" placeholder="Pekerjaan"
                                        class="form-control<?php echo e($errors->has('pekerjaan') ? ' is-invalid': ''); ?>"
                                        name="pekerjaan" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('pekerjaan')); ?>

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
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/create_alumni.blade.php ENDPATH**/ ?>