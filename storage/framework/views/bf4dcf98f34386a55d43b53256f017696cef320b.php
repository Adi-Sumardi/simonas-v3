<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Karakter Islami</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Karakter Islami Warga</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Warga</th>
				<th>Kegiatan</th>
				<th>Waktu</th>
				<th>Tempat</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $karakters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karakter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($karakter->nama_warga); ?></td>
				<td><?php echo e($karakter->kegiatan); ?></td>
				<td><?php echo e($karakter->waktu); ?></td>
				<td><?php echo e($karakter->tempat); ?></td>
				<td><?php echo e($karakter->keterangan); ?></td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/karakter_pdf.blade.php ENDPATH**/ ?>