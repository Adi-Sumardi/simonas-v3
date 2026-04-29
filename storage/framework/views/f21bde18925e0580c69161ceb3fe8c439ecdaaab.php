<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Kegiatan</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Kegiatan Aplikasi SIMONAS</h4>
	</center>

	<table class='table table-bordered table-striped'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Kegiatan</th>
				<th>Penyelenggara</th>
				<th>Jenis Kegiatan</th>
				<th>Waktu</th>
				<th>Tempat</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($kegiatan->nama_kegiatan); ?></td>
				<td><?php echo e($kegiatan->penyelenggara); ?></td>
				<td><?php echo e($kegiatan->jenis_kegiatan); ?></td>
				<td><?php echo e($kegiatan->waktu); ?></td>
				<td><?php echo e($kegiatan->tempat); ?></td>
				<td><?php echo e($kegiatan->keterangan); ?></td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kegiatan/kegiatan_pdf.blade.php ENDPATH**/ ?>