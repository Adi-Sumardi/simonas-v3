<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Karakter Islami Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Karakter Islami</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/karakter/update/<?php echo e($karakter->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6" hidden>
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label><span
                                    class="text-danger">
                            <input value="<?php echo e(Auth::user()->name); ?>" readonly type="text" class="form-control" name="nama_warga">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Karakter Islami:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control">
                                <option value="<?php echo e($karakter->komponen); ?>"><?php echo e($karakter->komponen); ?></option>
                                <option value="Membaca Al Quran, hafalan, hadits pilihan">Membaca Al Quran, hafalan, hadits pilihan</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Mengiktui kajian, membaca buku atau ceramah agama">Mengiktui kajian, membaca buku atau ceramah agama</option>
                                <option value="Menjadi imam shalat berjamaah atau memimpin doa">Menjadi imam shalat berjamaah atau memimpin doa</option>
                                <option value="Mengamalkan ibadah harian; shalat, puasa, zakat dll">Mengamalkan ibadah harian; shalat, puasa, zakat dll</option>
                                <option value="Menyampaikan dakwah, kultum, baik lisan, tulisan">Menyampaikan dakwah, kultum, baik lisan, tulisan</option>
                                <option value="Memelihara kebersihan (kamar, lingkungan dll)">Memelihara kebersihan (kamar, lingkungan dll)</option>
                                <option value="Mengajar pengajian, TPA, TPQ, dll">Mengajar pengajian, TPA, TPQ, dll</option>
                                <option value="Memelihara silaturahmi dan menolong sesama">Memelihara silaturahmi dan menolong sesama</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama"
                                   class="control-label">Asrama:</label><span
                                    class="text-danger">
                            <select name="asrama" class="form-control <?php echo e($errors->has('asrama') ? ' is-invalid': ''); ?>">
                                <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                <option value="Asrama Putri">Asrama Putri</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('asrama')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($karakter->kegiatan); ?>" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($karakter->waktu); ?>" type="date" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Tempat Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($karakter->tempat); ?>" type="text" class="form-control" name="tempat">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                            <textarea value="<?php echo e($karakter->keterangan); ?>" type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file" class="control-label">Lampiran File:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($karakter->file); ?>" type="file" class="form-control" name="file">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/karakter/edit.blade.php ENDPATH**/ ?>