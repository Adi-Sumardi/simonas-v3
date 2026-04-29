

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Kreatifitas dan Wirausaha Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Kreatifitas dan Wirausaha</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/mentor/kreatif/store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6" hidden>
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label>
                            <input id="nama_warga" readonly type="text" class="form-control" name="nama_warga"
                            value="<?php echo e(Auth::user()->name); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Kreativitas dan Kewirausahaan:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control <?php echo e($errors->has('komponen') ? ' is-invalid': ''); ?>">
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
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('komponen')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                    class="text-danger">
                            <input id="kegiatan" type="text"
                                   class="form-control<?php echo e($errors->has('kegiatan') ? ' is-invalid' : ''); ?>"
                                   placeholder="Masukan Nama Kegiatan" name="kegiatan" tabindex="1"
                                   value="<?php echo e(old('kegiatan')); ?>"
                                   required autofocus>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('kegiatan')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan
                                :</label><span
                                    class="text-danger">
                            <input id="waktu" type="date"
                                   class="form-control<?php echo e($errors->has('waktu') ? ' is-invalid': ''); ?>"
                                   placeholder="Masukan Waktu Kegiatan" name="waktu" tabindex="2" required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('waktu')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat"
                                   class="control-label">Tempat Kegiatan:</label><span
                                    class="text-danger">
                            <input id="tempat" type="text" placeholder="Masukan Tempat Kegiatan"
                                   class="form-control<?php echo e($errors->has('tempat') ? ' is-invalid': ''); ?>"
                                   name="tempat" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('tempat')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan"
                                   class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                                <textarea id="keterangan" type="keterangan" placeholder="Keterangan Kegiatan"
                                        class="form-control<?php echo e($errors->has('keterangan') ? ' is-invalid': ''); ?>"
                                        name="keterangan" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('keterangan')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file"
                                   class="control-label">Lampiran File: (max. file 1 MB)</label><span
                                    class="text-danger">*
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
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/kreatif/create.blade.php ENDPATH**/ ?>