<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Profile User</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Update Data Profile User</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/profile-store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">User ID:</label><span
                                    class="text-danger">*</span>
                            <input id="user_id" type="text"
                                   class="form-control<?php echo e($errors->has('user_id') ? ' is-invalid' : ''); ?>"
                                   name="user_id"
                                   tabindex="1" placeholder="Masukan User ID" value="<?php echo e(old('user_id')); ?>"
                                   autofocus required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('user_id')); ?>

                            </div>
                        </div>
                    </div>

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
                            <label for="nik">NIK:</label><span
                                    class="text-danger">*</span>
                            <input id="nik" type="text"
                                   class="form-control<?php echo e($errors->has('nik') ? ' is-invalid' : ''); ?>"
                                   placeholder="Masukan Nomor Induk Kependudukan" name="nik" tabindex="1"
                                   value="<?php echo e(old('nik')); ?>"
                                   required autofocus>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('nik')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat" class="control-label">Alamat
                                :</label><span
                                    class="text-danger">*</span>
                            <textarea id="alamat" type="text"
                                   class="form-control<?php echo e($errors->has('alamat') ? ' is-invalid': ''); ?>"
                                   placeholder="Masukan alamat lengkap" name="alamat" tabindex="2" required></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('alamat')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp"
                                   class="control-label">Nomor Telpone:</label><span
                                    class="text-danger">*</span>
                            <input id="no_telp" type="text" placeholder="Masukan Nomor Telpon"
                                   class="form-control<?php echo e($errors->has('no_telp') ? ' is-invalid': ''); ?>"
                                   name="no_telp" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('no_telp')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asal_sekolah"
                                   class="control-label">Asal Sekolah:</label><span
                                    class="text-danger">*</span>
                            <input id="asal_sekolah" type="text" placeholder="Masukan asal sekolah"
                                   class="form-control<?php echo e($errors->has('asal_sekolah') ? ' is-invalid': ''); ?>"
                                   name="asal_sekolah" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('asal_sekolah')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_lahir"
                                   class="control-label">Tanggal Lahir:</label><span
                                    class="text-danger">*</span>
                            <input id="tgl_lahir" type="text" placeholder="Masukan Tanggal Lahir"
                                   class="form-control<?php echo e($errors->has('tgl_lahir') ? ' is-invalid': ''); ?>"
                                   name="tgl_lahir" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('tgl_lahir')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prestasi"
                                   class="control-label">Prestasi:</label><span
                                    class="text-danger">*</span>
                            <textarea id="prestasi" type="text" placeholder="Masukan Prestasi"
                                   class="form-control<?php echo e($errors->has('prestasi') ? ' is-invalid': ''); ?>"
                                   name="prestasi" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('prestasi')); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="organisasi"
                                   class="control-label">Organisasi:</label><span
                                    class="text-danger">*</span>
                            <textarea id="organisasi" type="text" placeholder="Masukan Organisasi"
                                   class="form-control<?php echo e($errors->has('organisasi') ? ' is-invalid': ''); ?>"
                                   name="organisasi" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('organisasi')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ayah"
                                   class="control-label">Nama Ayah:</label><span
                                    class="text-danger">*</span>
                            <input id="nama_ayah" type="text" placeholder="Masukan Nama Ayah"
                                   class="form-control<?php echo e($errors->has('nama_ayah') ? ' is-invalid': ''); ?>"
                                   name="nama_ayah" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('nama_ayah')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ibu"
                                   class="control-label">Nama Ibu:</label><span
                                    class="text-danger">*</span>
                            <input id="nama_ibu" type="text" placeholder="Masukan Nama Ibu"
                                   class="form-control<?php echo e($errors->has('nama_ibu') ? ' is-invalid': ''); ?>"
                                   name="nama_ibu" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('nama_ibu')); ?>

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/profile/create.blade.php ENDPATH**/ ?>