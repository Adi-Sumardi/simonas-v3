<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Kreatifitas & Wirausaha</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Kreatifitas & Wirausaha Warga</h4>
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
			@foreach($kreatifs as $kreatif)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$kreatif->nama_warga}}</td>
				<td>{{$kreatif->kegiatan}}</td>
				<td>{{$kreatif->waktu}}</td>
				<td>{{$kreatif->tempat}}</td>
				<td>{{$kreatif->keterangan}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>