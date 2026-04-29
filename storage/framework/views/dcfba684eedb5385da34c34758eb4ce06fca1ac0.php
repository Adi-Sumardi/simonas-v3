

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Warga</h3>
    </div>
    <div class="section-body">
        <div class="row">
            
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Detail Warga</h4></div>
        
        <div class="card-body pt-1">
            <form action="/admin/warga-detail/<?php echo e($user->id); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="card-body pt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Foto Profil:</label>
                                <p><img src="<?php echo e($user->file); ?>" alt="" srcset=""></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap:</label>
                                <p><?php echo e($user->name); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Email:</label>
                                <p><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Asrama:</label>
                                <p><?php echo e($user->asrama); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Masuk Asrama:</label>
                                <p><?php echo e($user->tgl_masuk); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Keluar Asrama:</label><span class="text-danger">* (Diisi ketika sudah menjadi ALUMNI)</span>
                                <p><?php echo e($user->tgl_keluar); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Universitas:</label>
                                <p><?php echo e($user->universitas); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Fakultas:</label>
                                <p><?php echo e($user->fakultas); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Program Studi:</label>
                                <p><?php echo e($user->prodi); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Angkatan:</label>
                                <p><?php echo e($user->angkatan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Seminar Proposal:</label>
                                <p><?php echo e($user->tgl_seminar); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Skripsi:</label>
                                <p><?php echo e($user->tgl_skripsi); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Wisuda:</label>
                                <p><?php echo e($user->tgl_wisuda); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK:</label>
                                <p><?php echo e($user->nik); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat" class="control-label">Alamat:</label>
                                <p><?php echo e($user->alamat); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telp" class="control-label">No Telpon:</label>
                                <p><?php echo e($user->no_telp); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Asal Sekolah:</label>
                                <p><?php echo e($user->asal_sekolah); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Tanggal Lahir:</label>
                                <p><?php echo e($user->tgl_lahir); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Prestasi:</label>
                                <p><?php echo e($user->prestasi); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Organisasi:</label>
                                <p><?php echo e($user->organisasi); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Nama Ayah:</label>
                                <p><?php echo e($user->nama_ayah); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Nama Ibu:</label>
                                <p><?php echo e($user->nama_ibu); ?></p>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </form>
        </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/detail_warga.blade.php ENDPATH**/ ?>