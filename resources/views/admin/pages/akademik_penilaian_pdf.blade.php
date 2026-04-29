<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Penilaian Akademik</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Penilaian Akademik Warga</h4>
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
			@foreach($akademiks as $akademik)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$akademik->nama_warga}}</td>
				<td>{{$akademik->kegiatan}}</td>
				<td>{{$akademik->waktu}}</td>
				<td>{{$akademik->nama_penilai}}</td>
				<td>{{$akademik->nilai}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>