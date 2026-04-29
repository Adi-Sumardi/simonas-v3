@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Warga</h3>
    </div>
    <div class="section-body">
        <div class="row">
            
        </div>
    </div>
    <div class="card">
        <div class="p-3 mb-2 bg-primary text-white">Detail Profil Warga</div>
        <div class="card-body pt-1">
            <form action="/mentor/warga-update/{{$user->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="card-body pt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="img-responsive" alt="avatar"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <ul class="list-group">
                                    <li class="list-group-item active" aria-current="true">Aktifitas <i class="fa fa-dashboard fa-1x"></i></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Akademik</strong><span class="badge badge-primary badge-pill">{{$akademiks}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Leadership</strong><span class="badge badge-primary badge-pill">{{$leaderships}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Karakter Islami</strong><span class="badge badge-primary badge-pill">{{$karakters}}</span> </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Kreatifitas</strong><span class="badge badge-primary badge-pill">{{$kreatifs}}</span> </li>
                                  </ul> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap:</label>
                                <p>{{$user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Email:</label>
                                <p>{{$user->email}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Asrama:</label>
                                <p>{{$user->asrama}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Masuk Asrama:</label>
                                <p>{{$user->tgl_masuk}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Keluar Asrama:</label><span class="text-danger">* (Diisi ketika sudah menjadi ALUMNI)</span>
                                <p>{{$user->tgl_keluar}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Universitas:</label>
                                <p>{{$user->universitas}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Fakultas:</label>
                                <p>{{$user->fakultas}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Program Studi:</label>
                                <p>{{$user->prodi}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Angkatan:</label>
                                <p>{{$user->angkatan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Seminar Proposal:</label>
                                <p>{{$user->tgl_seminar}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Skripsi:</label>
                                <p>{{$user->tgl_skripsi}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Tanggal Wisuda:</label>
                                <p>{{$user->tgl_wisuda}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK:</label>
                                <p>{{$user->nik}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat" class="control-label">Alamat:</label>
                                <p>{{$user->alamat}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telp" class="control-label">No Telpon:</label>
                                <p>{{$user->no_telp}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Asal Sekolah:</label>
                                <p>{{$user->asal_sekolah}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Tanggal Lahir:</label>
                                <p>{{$user->tgl_lahir}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Prestasi:</label>
                                <p>{{$user->prestasi}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Organisasi:</label>
                                <p>{{$user->organisasi}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Nama Ayah:</label>
                                <p>{{$user->nama_ayah}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asal_sekolah" class="control-label">Nama Ibu:</label>
                                <p>{{$user->nama_ibu}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</section>
@endsection