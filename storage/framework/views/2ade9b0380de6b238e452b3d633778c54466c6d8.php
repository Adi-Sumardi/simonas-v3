

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Kegiatan Karakter Islami</h3>
    </div>
    <div class="section-body">
        <div class="row">
        </div>
    </div>
    <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
        <div class="card-header"><h4>Detail Kegiatan Karakter Islami</h4></div>
        
        <div class="card-body pt-1">
            <form action="/admin/penilaian/karakter/update/<?php echo e($karakter->id); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_warga">Nama Warga:</label>
                                <p><?php echo e($karakter->nama_warga); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="komponen">Komponen Kurikulum Karakter Islami:</label>
                                <p><?php echo e($karakter->komponen); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kegiatan">Nama Kegiatan:</label>
                                <p><?php echo e($karakter->kegiatan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waktu">Waktu Kegiatan:</label>
                                <p><?php echo e($karakter->waktu); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat">Tempat Kegiatan:</label>
                                <p><?php echo e($karakter->tempat); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Uraian Kegiatan:</label>
                                <p><?php echo e($karakter->keterangan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat" class="control-label">Nama Penilai:</label><span class="text-danger">*</span>
                                <p><?php echo e($karakter->nama_penilai); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="control-label">Nilai:</label><span class="text-danger">*</span>
                                <p><?php echo e($karakter->nilai); ?></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan">Lampiran File:</label>
                                <p><iframe src="<?php echo e(url('storage/'.$karakter->file)); ?>" frameborder="0" style="width: 300px; height: 200px; text-align: center;"></iframe></p>
                                <a href="/admin/karakter/file/download/<?php echo e($karakter->file); ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download File</a>
                            </div>
                        </div>
                    </div>                  
                </div>
            </form>
        </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/penilaian_karakter_detail.blade.php ENDPATH**/ ?>