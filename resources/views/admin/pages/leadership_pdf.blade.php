<!DOCTYPE html>
<html>
<head>
	<title>Laporan Data Leadership</title>
</head>
<body data-rsssl=1>
	<style type="text/css"> table tr td, table tr th{ font-size: 9pt; } </style>
	<center>
		<h5>Laporan Data Leadership Warga</h4>
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
			@foreach($leaderships as $leadership)
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$leadership->nama_warga}}</td>
				<td>{{$leadership->kegiatan}}</td>
				<td>{{$leadership->waktu}}</td>
				<td>{{$leadership->tempat}}</td>
				<td>{{$leadership->keterangan}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>