<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Request Akun SIMONAS</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Request Akun Aplikasi SIMONAS</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Lengkap</th>
				<th>Email</th>
				<th>Password</th>
				<th>Role</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($form->name); ?></td>
				<td><?php echo e($form->email); ?></td>
				<td><?php echo e($form->no_wa); ?></td>
				<td><?php echo e($form->status); ?></td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>

</body>
</html><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/akun/request_pdf.blade.php ENDPATH**/ ?>