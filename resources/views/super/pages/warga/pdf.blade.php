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
			@foreach($users as $user)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$user->name}}</td>
				<td>{{$user->universitas}}</td>
				<td>{{$user->asrama}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>