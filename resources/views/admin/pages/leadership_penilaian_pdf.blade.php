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
			@foreach($leaderships as $leadership)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$leadership->nama_warga}}</td>
				<td>{{$leadership->kegiatan}}</td>
				<td>{{$leadership->waktu}}</td>
				<td>{{$leadership->nama_penilai}}</td>
				<td>{{$leadership->nilai}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>