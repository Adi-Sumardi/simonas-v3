<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Penilaian Leadership</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Penilaian Leadership Warga</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Lengkap</th>
				<th>Kegiatan</th>
				<th>Waktu</th>
				<th>Nama Penilai</th>
				<th>Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $leaderships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leadership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($leadership->nama_warga); ?></td>
				<td><?php echo e($leadership->kegiatan); ?></td>
				<td><?php echo e($leadership->waktu); ?></td>
				<td><?php echo e($leadership->nama_penilai); ?></td>
				<td><?php echo e($leadership->nilai); ?></td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/admin/pages/leadership_penilaian_pdf.blade.php ENDPATH**/ ?>