@extends('alumni.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Warga</h3>
    </div>
    <div class="section-body">
        <div class="row">
            {{-- <div class="col-12 col-md-12 col-lg-12">
                <a href="/kegiatan/detail/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Kegiatan PDF</a>
            </div> --}}
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Detail Warga</h4></div>
        
        <div class="card-body pt-1">
            <form action="/warga-update/{{$user->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="card-body pt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Foto Profil:</label>
                                <br>
                                <p><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="avatar img-circle img-thumbnail" alt="avatar" style="width:125px; height:125px;"></p>
                            </div>
                        </div>
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