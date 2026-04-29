<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Kreatifitas dan Wirausaha Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kreatifitas dan Wirausaha Islami</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/kreatif/update/<?php echo e($kreatif->id); ?>" enctype="multipart/form-data">
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
                                <option value="<?php echo e($kreatif->komponen); ?>"><?php echo e($kreatif->komponen); ?></option>
                                <option value="">-Pilih Kompetensi-</option>
                                <option value="Mengikuti pelatihan kreativitas dan kewirausahaan">Mengikuti pelatihan kreativitas dan kewirausahaan</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Membaca buku, majalah, internet dll tentang kewirausahaan">Membaca buku, majalah, internet dll tentang kewirausahaan</option>
                                <option value="Mengikuti forum ceramah atau diskusi kewirausahaan">Mengikuti forum ceramah atau diskusi kewirausahaan</option>
                                <option value="Melakukan tugas dalam kegiatan usaha asrama">Melakukan tugas dalam kegiatan usaha asrama</option>
                                <option value="Menulis proposal usaha">Menulis proposal usaha</option>
                                <option value="Menghasilkan karya kreatif (video, grafis, dll)">Menghasilkan karya kreatif (video, grafis, dll)</option>
                                <option value="Memiliki keberanian untuk memulai usaha">Memiliki keberanian untuk memulai usaha</option>
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
                            <input value="<?php echo e($kreatif->kegiatan); ?>" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($kreatif->waktu); ?>" type="date" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Tempat Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($kreatif->tempat); ?>" type="text" class="form-control" name="tempat">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                            <textarea value="<?php echo e($kreatif->keterangan); ?>" type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file" class="control-label">Lampiran File:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($kreatif->file); ?>" type="file" class="form-control" name="file">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kreatif/edit.blade.php ENDPATH**/ ?>