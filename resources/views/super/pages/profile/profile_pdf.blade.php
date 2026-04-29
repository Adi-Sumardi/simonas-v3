<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Profile PDF</title>
</head>
<body>
    <center>
		<h5>Laporan Data Profile Warga</h4>
	</center>
    <div class="card-body pt-1">
                
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->name}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Email:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->email}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Asrama:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->asrama}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Tanggal Masuk Asrama:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->tgl_masuk}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Tanggal Keluar Asrama:</label><span class="text-danger">* (Diisi ketika sudah menjadi ALUMNI)</span>
                        <p>{{Auth::user()->tgl_keluar}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Universitas:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->universitas}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Fakultas:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->fakultas}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Program Studi:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->prodi}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Angkatan:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->angkatan}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Tanggal Seminar Proposal:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->tgl_seminar}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Tanggal Skripsi:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->tgl_skripsi}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Tanggal Wisuda:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->tgl_wisuda}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik">NIK:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->nik}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat" class="control-label">Alamat:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->alamat}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_telp" class="control-label">No Telpon:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->no_telp}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Asal Sekolah:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->asal_sekolah}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Tanggal Lahir:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->tgl_lahir}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Prestasi:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->prestasi}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Organisasi:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->organisasi}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Nama Ayah:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->nama_ayah}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asal_sekolah" class="control-label">Nama Ibu:</label><span class="text-danger">*</span>
                        <p>{{Auth::user()->nama_ibu}}</p>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</body>
</html>