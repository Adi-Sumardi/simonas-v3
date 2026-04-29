@extends('mahasiswa.layouts.master-layouts')

@section('title')
Profile
@endsection

@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Select2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
@endsection

@section('content')
@component('mahasiswa.common-components.breadcrumb')
@slot('title')
Profile
@endslot
@slot('li_1')
Pages
@endslot
@endcomponent

    <div class="row">
        {{-- Foto --}}
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Update Foto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/mahasiswa-data-foto-update/{{Auth::user()->id}}" method="POST"
                            enctype="multipart/form-data">
                            @method('PATCH')
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="form-group">
                                        <input type="file" name="avatar" required="required" id="image">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <img id="preview-image-before-upload"
                                        src="https://static.vecteezy.com/system/resources/previews/005/337/799/original/icon-image-not-found-free-vector.jpg"
                                        alt="preview image" style="max-height: 250px;">
                                </div>
                                <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        {{-- Nama Warga --}}
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-nama-warga-modal-center" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Update Nama</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/mahasiswa-data-nama-update/{{Auth::user()->id}}" method="POST"
                            enctype="multipart/form-data">
                            @method('PATCH')
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="form-group col-12">
                                        <div class="form-group">
                                            <label for="name">Nama:</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ Auth::user()->name}}">
                                        </div>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        {{-- Status Warga --}}
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-status-modal-center" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Update Status Warga</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/mahasiswa-data-status-update/{{Auth::user()->id}}" method="POST"
                            enctype="multipart/form-data">
                            @method('PATCH')
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="form-group col-6">
                                        <label for="status_warga" class="control-label">Status Warga Asrama:</label>
                                        <select name="status_warga" class="form-control">
                                            <option disabled>Pilih Status Warga</option>
                                            <option value="Warga Percobaan">Warga Percobaan</option>
                                            <option value="Pengurus Asrama">Pengurus Asrama</option>
                                            <option value="Warga Tetap">Warga Tetap</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        {{-- Info --}}
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-info-modal-xl" tabindex="-1" role="dialog"
                aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Update Personal Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/mahasiswa-data-info-update/{{Auth::user()->id}}" method="POST"
                            enctype="multipart/form-data">
                            @method('PATCH')
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <h5 style="text-align: center" class="mb-4"><strong>DATA PERSONAL</strong></h5>
                                    <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span
                                            style="color: red">*</span>) pada form wajib diisi!</p>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="asrama">Asrama:</label>
                                                    <select
                                                        class="form-control @error('asrama') is-invalid @enderror"
                                                        name="asrama">
                                                        <option value="{{ Auth::user()->asrama}}">{{
                                                            Auth::user()->asrama}}</option>
                                                        <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung
                                                            Djati</option>
                                                        <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                                        <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                                        <option value="Asrama Putri">Asrama Putri</option>
                                                    </select>
                                                </div>
                                                @error('asrama')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_induk">Nomor Induk Warga<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="number"
                                                        class="form-control @error('no_induk') is-invalid @enderror"
                                                        name="no_induk" value="{{ Auth::user()->no_induk}}">
                                                </div>
                                                @error('no_induk')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_masuk">Tanggal Masuk<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_masuk') is-invalid @enderror"
                                                        name="tgl_masuk" value="{{ Auth::user()->tgl_masuk}}">
                                                </div>
                                                @error('tgl_masuk')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nik">NIK<span style="color: red">*</span>:</label>
                                                    <input type="number"
                                                        class="form-control @error('nik') is-invalid @enderror"
                                                        name="nik" value="{{ Auth::user()->nik}}">
                                                </div>
                                                @error('nik')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_lahir">Tanggal Lahir<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                        name="tgl_lahir" value="{{ Auth::user()->tgl_lahir}}">
                                                </div>
                                                @error('tgl_lahir')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="alamat">Alamat Jalan<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('alamat') is-invalid @enderror"
                                                        name="alamat" value="{{ Auth::user()->alamat}}">
                                                </div>
                                                @error('alamat')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kecamatan">Kecamatan<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('kecamatan') is-invalid @enderror"
                                                        name="kecamatan" value="{{ Auth::user()->kecamatan}}">
                                                </div>
                                                @error('kecamatan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kota">Kota/Kabupaten<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('kota') is-invalid @enderror"
                                                        name="kota" value="{{ Auth::user()->kota}}">
                                                </div>
                                                @error('kota')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="provinsi">Provinsi<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('provinsi') is-invalid @enderror"
                                                        name="provinsi" value="{{ Auth::user()->provinsi}}">
                                                </div>
                                                @error('provinsi')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kode_pos">Kode POS<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="number"
                                                        class="form-control @error('kode_pos') is-invalid @enderror"
                                                        name="kode_pos" value="{{ Auth::user()->kode_pos}}">
                                                </div>
                                                @error('kode_pos')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_telp">Nomor Telepon<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="number"
                                                        class="form-control @error('no_telp') is-invalid @enderror"
                                                        name="no_telp" value="{{ Auth::user()->no_telp}}">
                                                </div>
                                                @error('no_telp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="asal_sekolah">Asal Sekolah<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('asal_sekolah') is-invalid @enderror"
                                                        name="asal_sekolah" value="{{ Auth::user()->asal_sekolah}}">
                                                </div>
                                                @error('asal_sekolah')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_ayah">Nama Ayah<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('nama_ayah') is-invalid @enderror"
                                                        name="nama_ayah" value="{{ Auth::user()->nama_ayah}}">
                                                </div>
                                                @error('nama_ayah')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_ibu">Nama Ibu<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('nama_ibu') is-invalid @enderror"
                                                        name="nama_ibu" value="{{ Auth::user()->nama_ibu}}">
                                                </div>
                                                @error('nama_ibu')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5 style="text-align: center" class="mb-4"><strong>DATA PENDIDIKAN</strong>
                                    </h5>
                                    <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span
                                            style="color: red">*</span>) pada form wajib diisi!</p>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="universitas">Universitas<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('universitas') is-invalid @enderror"
                                                        name="universitas" value="{{ Auth::user()->universitas}}">
                                                </div>
                                                @error('universitas')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fakultas">Fakultas<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('fakultas') is-invalid @enderror"
                                                        name="fakultas" value="{{ Auth::user()->fakultas}}">
                                                </div>
                                                @error('fakultas')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prodi">Program Studi<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="text"
                                                        class="form-control @error('prodi') is-invalid @enderror"
                                                        name="prodi" value="{{ Auth::user()->prodi}}">
                                                </div>
                                                @error('prodi')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="angkatan">Tahun Angkatan<span
                                                            style="color: red">*</span>:</label>
                                                    <input type="number"
                                                        class="form-control @error('angkatan') is-invalid @enderror"
                                                        name="angkatan" value="{{ Auth::user()->angkatan}}">
                                                </div>
                                                @error('angkatan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_seminar">Tanggal Seminar Skripsi:</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_seminar') is-invalid @enderror"
                                                        name="tgl_seminar" value="{{ Auth::user()->tgl_seminar}}">
                                                </div>
                                                @error('tgl_seminar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_skripsi">Tanggal Sidang Skripsi:</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_skripsi') is-invalid @enderror"
                                                        name="tgl_skripsi" value="{{ Auth::user()->tgl_skripsi}}">
                                                </div>
                                                @error('tgl_seminar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_wisuda">Tanggal Wisuda:</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_wisuda') is-invalid @enderror"
                                                        name="tgl_wisuda" value="{{ Auth::user()->tgl_wisuda}}">
                                                </div>
                                                @error('tgl_wisuda')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prestasi">Prestasi:</label>
                                                    <input type="text"
                                                        class="form-control @error('prestasi') is-invalid @enderror"
                                                        name="prestasi" value="{{ Auth::user()->prestasi}}">
                                                </div>
                                                @error('prestasi')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="organisasi">Organisasi<span
                                                            style="color: red">*</span>:</label>
                                                    {{-- <input type="text"
                                                        class="tm-input tm-input-info form-control @error('organisasi') is-invalid @enderror"
                                                        --}} <input type="text"
                                                        class="form-control @error('organisasi') is-invalid @enderror"
                                                        name="organisasi" value="{{ Auth::user()->organisasi}}">
                                                </div>
                                                @error('organisasi')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6 col-12">
                                            <div class="btn" data-toggle="modal" data-target=".bs-example-modal-center">
                                                <img src="{{ url('/data_photo/' . Auth::user()->avatar) }}" alt=""
                                                    class="avatar-lg mx-auto img-thumbnail rounded-circle">
                                                    <br>
                                                <p class="badge badge-soft-info">Tap untuk merubah photo</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <h4 class="text-dark">{{ Auth::user()->name }}</h4>
                                            <p class="text-body">{{ Auth::user()->asrama }}</p>
                                            <p class="text-body mb-5">{{ Auth::user()->universitas }}</p>
                                            <br>
                                            <br>
                                            <div class="text-right" style="position: absolute; bottom: 20px; right: 20px;">
                                                <a href="/mahasiswa-profile-nama-edit/{{Auth::user()->id}}" class="btn"
                                                    data-toggle="modal" data-target=".bs-nama-warga-modal-center">
                                                    <i class="mdi mdi-pencil-outline"> Edit Nama</i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <h5 class="mb-2">Status Warga</h5>
                                                    <label class="mb-0 badge badge-soft-info font-size-14">{{ Auth::user()->status_warga}}</label>
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <a href="/mahasiswa-profile-status-edit/{{Auth::user()->id}}"
                                                        class="btn btn-outline-primary" data-toggle="modal"
                                                        data-target=".bs-status-modal-center">
                                                        <i class="mdi mdi-pencil-outline"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <h5 class="mb-2">Nomor Induk Warga</h5>
                                            <label
                                                class="mb-0 badge badge-soft-primary font-size-14">{{Auth::user()->no_induk}}</label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <h5 class="mb-2">Mentor</h5>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">-</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    <h5 class="card-title mb-3">Personal Information</h5>
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <a href="/mahasiswa-data-info-edit/{{Auth::user()->id}}" class="btn btn-outline-primary"
                                                        data-toggle="modal" data-target=".bs-info-modal-xl">
                                                        <i class="mdi mdi-account-edit-outline font-size-16"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Email</h6>
                                            <label
                                                class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->email }}</label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Nomor Telepon</h6>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->no_telp }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <h5 class="card-title mb-3">Tanggal Lahir</h5>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ date('d-m-Y', strtotime(Auth::user()->tgl_lahir))}}</label>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Alamat Asal</h6>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->alamat }}</label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Kecamatan</h6>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->kecamatan }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <h5 class="card-title mb-3">Kota/Kabupaten</h5>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->kota }}</label>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Provinsi</h6>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->provinsi }}</label>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <h6 class="mb-2">Organisasi</h6>
                                            <label class="mb-0 badge badge-soft-primary font-size-14">{{ Auth::user()->organisasi}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- Required datatable js -->
<script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ URL::asset('/js/pages/datatables.init.js') }}"></script>

<!-- apexcharts -->
<script src="{{ URL::asset('/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/profile.init.js') }}"></script>

<!-- apexcharts init -->
<script src="{{ URL::asset('/js/pages/apexcharts.init.js') }}"></script>

<!-- Select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Sweet Alerts js -->
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Sweet alert init js-->
<script src="{{ URL::asset('/js/pages/sweet-alerts.init.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.js"></script>

<script src="{{URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- Chart JS -->
<script src="{{URL::asset('/libs/chart-js/chart-js.min.js')}}"></script>
<script src="{{URL::asset('/js/pages/chartjs.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    $(document).ready(function (e) {


           $('#image').change(function(){

            let reader = new FileReader();

            reader.onload = (e) => {

              $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

           });

        });

</script>

<script>
    $(document).ready(function() {
            // Inisialisasi Select2 untuk semua dropdown
            $('#data_komponen_akademik, #data_komponen_leadership, #data_komponen_karakter, #data_komponen_kreatif').select2();

            // Event handler saat terjadi perubahan pada semua dropdown
            $('#data_komponen_akademik, #data_komponen_leadership, #data_komponen_karakter, #data_komponen_kreatif').on('change', function() {
                // Mendapatkan elemen yang dipilih
                const selectedOption = $(this).find('option:selected');

                // Mendapatkan nilai data-id dari elemen yang dipilih
                const komponenId = selectedOption.data('id');

                // Mengisi nilai komponen_id ke dalam input dengan ID data
                $("#data").val(komponenId);
            });
        });
</script>

<script type="text/javascript">
    $(".tm-input").tagsManager();
</script>

<script>
    $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $('#searchButton').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                filterTableByDateRange(startDate, endDate);
            } else {
                alert('Silahkan tanggal mulai dan tanggal akhirnya');
            }
        });
</script>

@endsection
