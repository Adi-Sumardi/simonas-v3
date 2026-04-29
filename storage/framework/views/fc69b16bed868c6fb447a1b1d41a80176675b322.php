

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Asrama YAPI</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/asrama/store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_asrama"
                                   class="control-label">Nama Asrama:</label><span
                                    class="text-danger">
                            <select name="nama_asrama" class="form-control <?php echo e($errors->has('nama_asrama') ? ' is-invalid': ''); ?>">
                                <option value="ASGJ">Asrama Sunan Gunung Djati</option>
                                <option value="ASG">Asrama Sunan Giri</option>
                                <option value="AWS">Asrama Wali Songo</option>
                                <option value="DQF">Daarul Qur'an Fatahillah</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('nama_asrama')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"
                                   class="control-label">Alamat:</label><span
                                    class="text-danger">
                            <input id="password_confirmation" type="text" placeholder="Masukan Alamat Asrama"
                                   class="form-control<?php echo e($errors->has('alamat_asrama') ? ' is-invalid': ''); ?>"
                                   name="alamat_asrama" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('alamat_asrama')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun_jabatan" class="control-label">Tahun Jabatan
                                :</label><span
                                    class="text-danger">*</span>
                            <input id="tahun_jabatan" type="date"
                                   class="form-control<?php echo e($errors->has('tahun_jabatan') ? ' is-invalid': ''); ?>"
                                   placeholder="Masukan Tahun Jabatan di Asrama" name="tahun_jabatan" tabindex="2" required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('tahun_jabatan')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role"
                                   class="control-label">Nama Direktur:</label><span
                                    class="text-danger">*</span>
                            <input id="password_confirmation" type="text" placeholder="Masukan Nama Direktur Asrama"
                                    class="form-control<?php echo e($errors->has('direktur') ? ' is-invalid': ''); ?>"
                                    name="direktur" tabindex="2">
                            <div class="invalid-feedback">
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('direktur')); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role"
                                   class="control-label">Nama Ketua:</label><span
                                    class="text-danger">*</span>
                            <input id="password_confirmation" type="text" placeholder="Masukan Nama Ketua Asrama"
                                    class="form-control<?php echo e($errors->has('ketua') ? ' is-invalid': ''); ?>"
                                    name="ketua" tabindex="2">
                            <div class="invalid-feedback">
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('ketua')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="cancel" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/create_asrama.blade.php ENDPATH**/ ?>