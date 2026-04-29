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
			@foreach($kegiatans as $kegiatan)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$kegiatan->nama_kegiatan}}</td>
				<td>{{$kegiatan->penyelenggara}}</td>
				<td>{{$kegiatan->jenis_kegiatan}}</td>
				<td>{{$kegiatan->waktu}}</td>
				<td>{{$kegiatan->tempat}}</td>
				<td>{{$kegiatan->keterangan}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>