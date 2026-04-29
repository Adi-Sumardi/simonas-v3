<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Akademik</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Akademik Warga</h4>
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
			@foreach($akademiks as $akademik)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$akademik->nama_warga}}</td>
				<td>{{$akademik->kegiatan}}</td>
				<td>{{$akademik->waktu}}</td>
				<td>{{$akademik->tempat}}</td>
				<td>{{$akademik->keterangan}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>