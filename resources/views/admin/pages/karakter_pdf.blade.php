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
			@foreach($karakters as $karakter)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$karakter->nama_warga}}</td>
				<td>{{$karakter->kegiatan}}</td>
				<td>{{$karakter->waktu}}</td>
				<td>{{$karakter->tempat}}</td>
				<td>{{$karakter->keterangan}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>