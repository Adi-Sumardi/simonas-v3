

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Kegiatan</h3>
    </div>
    <div class="section-body">
        <div class="row">
            
        </div>
    </div>
    <div class="card card-primary" style="overflow: scroll;">
        <div class="card-header"><h4>Detail Kegiatan</h4></div>
        
        <div class="card-body pt-1">
            <form action="/admin/kegiatan-update/<?php echo e($kegiatan->id); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row">                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kegiatan">Nama Kegiatan:</label>
                                <p><?php echo e($kegiatan->nama_kegiatan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tujuan">Tujuan Kegiatan:</label>
                                <p><?php echo e($kegiatan->tujuan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penyelenggara">Penyelenggara:</label>
                                <p><?php echo e($kegiatan->penyelenggara); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                                <p><?php echo e($kegiatan->jenis_kegiatan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waktu">Waktu Kegiatan:</label>
                                <p><?php echo e($kegiatan->waktu); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat">Tempat Kegiatan:</label>
                                <p><?php echo e($kegiatan->tempat); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Uraian Kegiatan:</label>
                                <p><?php echo e($kegiatan->keterangan); ?></p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Lampiran File:</label>
                                <p><img width="300" src="<?php echo e(url ('/data_file/'.$kegiatan->file)); ?>"></p>
                            </div>
                        </div>
                    </div>                  
                </div>
            </form>
        </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/detail_kegiatan.blade.php ENDPATH**/ ?>