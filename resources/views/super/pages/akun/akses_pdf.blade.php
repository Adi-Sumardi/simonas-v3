<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Akses</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Akses Aplikasi SIMONAS</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Lengkap</th>
				<th>Email</th>
				<th>Role</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$user->name}}</td>
				<td>{{$user->email}}</td>
				<td>{{$user->role}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>