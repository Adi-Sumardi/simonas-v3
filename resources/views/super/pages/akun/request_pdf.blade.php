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
			@foreach($forms as $form)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$form->name}}</td>
				<td>{{$form->email}}</td>
				<td>{{$form->no_wa}}</td>
				<td>{{$form->status}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>