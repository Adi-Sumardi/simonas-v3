

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Leadership Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Leadership</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/mentor/leadership/update/<?php echo e($leadership->id); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($leadership->nama_warga); ?>" type="text" class="form-control" name="nama_warga">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Akademik:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control">
                                <option value="<?php echo e($leadership->komponen); ?>"><?php echo e($leadership->komponen); ?></option>
                                <option value="Mengikuti pelatihan kepemimpinan">Mengikuti pelatihan kepemimpinan</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Melaksanakan tugas kepanitiaan (mandat)">Melaksanakan tugas kepanitiaan (mandat)</option>
                                <option value="Melakukan tugas sebagai pengurus organisasi">Melakukan tugas sebagai pengurus organisasi</option>
                                <option value="Menjadi peserta atau memimpin rapat">Menjadi peserta atau memimpin rapat</option>
                                <option value="Mengikuti diskusi atau debat penyelesaian masalah">Mengikuti diskusi atau debat penyelesaian masalah</option>
                                <option value="Menulis surat, proposal kegiatan, laporan dll">Menulis surat, proposal kegiatan, laporan dll</option>
                                <option value="Memberikan kontribusi baik harta, tenaga, waktu">Memberikan kontribusi baik harta, tenaga, waktu</option>
                                <option value="Menyampaikan gagasan baik lisan atau tulisan">Menyampaikan gagasan baik lisan atau tulisan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($leadership->kegiatan); ?>" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($leadership->waktu); ?>" type="text" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Tempat Kegiatan:</label><span
                                class="text-danger">
                            <input value="<?php echo e($leadership->tempat); ?>" type="text" class="form-control" name="tempat">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                            <textarea value="<?php echo e($leadership->keterangan); ?>" type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file" class="control-label">Lampiran File:</label><span
                                    class="text-danger">
                            <input value="<?php echo e($leadership->file); ?>" type="file" class="form-control" name="file">
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
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/leadership/edit.blade.php ENDPATH**/ ?>