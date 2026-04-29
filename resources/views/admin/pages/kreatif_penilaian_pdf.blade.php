<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Penilaian Kreatifitas & Wirausaha</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Penilaian Kreatifitas & Wirausaha Warga</h4>
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
			@foreach($kreatifs as $kreatif)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$kreatif->nama_warga}}</td>
				<td>{{$kreatif->kegiatan}}</td>
				<td>{{$kreatif->waktu}}</td>
				<td>{{$kreatif->nama_penilai}}</td>
				<td>{{$kreatif->nilai}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>