<!DOCTYPE html>
<html>
<head>
	<title>Laporan Detail Data Kegiatan</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Detail Data Kegiatan</h4>
	</center>
    <div class="card-body pt-1">
        <form action="/kegiatan-update/<?php echo e($kegiatan->id); ?>" method="post">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div class="">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Kegiatan:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->nama_kegiatan); ?></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="penyelenggara">Penyelenggara:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->penyelenggara); ?></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="jenis_kegiatan">Jenis Kegiatan:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->jenis_kegiatan); ?></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="waktu">Waktu Kegiatan:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->waktu); ?></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="tempat">Tempat Kegiatan:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->tempat); ?></p>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label><span class="text-danger">*</span>
                            <p><?php echo e($kegiatan->keterangan); ?></p>
                        </div>
                    </div>
                </div>                  
            </div>
        </form>
    </div>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kegiatan/kegiatan_detail_pdf.blade.php ENDPATH**/ ?>