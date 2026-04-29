<!DOCTYPE html>
<html>
<head>
	<title>Data Warga Asrama YAPI</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Warga Asrama Aplikasi SIMONAS</h4>
	</center>

	<table class='table table-bordered table-striped'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Lengkap</th>
				<th>Kampus</th>
				<th>Asrama</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($user->name); ?></td>
				<td><?php echo e($user->universitas); ?></td>
				<td><?php echo e($user->asrama); ?></td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/warga/pdf.blade.php ENDPATH**/ ?>