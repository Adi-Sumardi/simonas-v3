@extends('mentor.layouts.master-layouts')

@section('title')
    Profile
@endsection

@section('css')
    <!-- Plugin css -->
    <link href="{{URL::asset('/libs/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    @component('mentor.common-components.breadcrumb')
        @slot('title')
            Profile
        @endslot
        @slot('li_1')
            Pages
        @endslot
    @endcomponent


    <!-- start row -->
    <div class="row">
        <div class="col-12">
            @if(session()->has('success-akademik'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Akademik berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('success-leadership'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Leadership berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('success-karakter'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Karakter Islami berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('success-kreatif'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Kreativitas & Kewirausahaan berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('success-ip'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Index Prestasi Akademik berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('info-foto'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Foto Profile berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('info-status'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Status Warga berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('info-personal'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Informasi Personal berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('info-ip'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Informasi Personal berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('info-khs'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Update!</strong> File Kartu Hasil Studi berhasil diupdate.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="col-md-12 col-xl-3">
            <div class="card">
                {{--  Foto  --}}
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Update Foto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/mentor-profile-foto-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">                            
                                    @method('PATCH')
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="file" name="avatar" required="required" id="image">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <img id="preview-image-before-upload" src="https://static.vecteezy.com/system/resources/previews/005/337/799/original/icon-image-not-found-free-vector.jpg"
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

                {{--  Info  --}}

                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-info-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Update Personal Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/mentor-profile-info-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                                    @method('PATCH')
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <h5 style="text-align: center" class="mb-4"><strong>DATA PERSONAL</strong></h5>
                                            <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="asrama">Asrama:</label>
                                                            <select class="form-control @error('asrama') is-invalid @enderror"
                                                                name="asrama">
                                                                <option value="{{ Auth::user()->asrama}}">{{ Auth::user()->asrama}}</option>
                                                                <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
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
                                                            <label for="no_induk">Nomor Induk Warga<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control @error('no_induk') is-invalid @enderror"
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
                                                            <label for="tgl_masuk">Tanggal Masuk<span style="color: red">*</span>:</label>
                                                            <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror"
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
                                                            <input type="number" class="form-control @error('nik') is-invalid @enderror"
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
                                                            <label for="tgl_lahir">Tanggal Lahir<span style="color: red">*</span>:</label>
                                                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
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
                                                            <label for="alamat">Alamat Jalan<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
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
                                                            <label for="kecamatan">Kecamatan<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
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
                                                            <label for="kota">Kota/Kabupaten<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('kota') is-invalid @enderror"
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
                                                            <label for="provinsi">Provinsi<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('provinsi') is-invalid @enderror"
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
                                                            <label for="kode_pos">Kode POS<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control @error('kode_pos') is-invalid @enderror"
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
                                                            <label for="no_telp">Nomor Telepon<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control @error('no_telp') is-invalid @enderror"
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
                                                            <label for="asal_sekolah">Asal Sekolah<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
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
                                                            <label for="nama_ayah">Nama Ayah<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
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
                                                            <label for="nama_ibu">Nama Ibu<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
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
                                            <h5 style="text-align: center" class="mb-4"><strong>DATA PENDIDIKAN</strong></h5>
                                            <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                            <div class="form-group">
                                                <div class="row">                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="universitas">Universitas<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('universitas') is-invalid @enderror"
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
                                                            <label for="fakultas">Fakultas<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('fakultas') is-invalid @enderror"
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
                                                            <label for="prodi">Program Studi<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control @error('prodi') is-invalid @enderror"
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
                                                            <label for="angkatan">Tahun Angkatan<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control @error('angkatan') is-invalid @enderror"
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
                                                            <input type="date" class="form-control @error('tgl_seminar') is-invalid @enderror"
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
                                                            <input type="date" class="form-control @error('tgl_skripsi') is-invalid @enderror"
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
                                                            <input type="date" class="form-control @error('tgl_wisuda') is-invalid @enderror"
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
                                                            <input type="text" class="form-control @error('prestasi') is-invalid @enderror"
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
                                                            <label for="organisasi">Organisasi<span style="color: red">*</span>:</label>
                                                            {{--  <input type="text" class="tm-input tm-input-info form-control @error('organisasi') is-invalid @enderror"  --}}
                                                            <input type="text" class="form-control @error('organisasi') is-invalid @enderror"
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

                <div class="card-body">
                    <div class="profile-widgets py-3">

                        <div class="text-center">
                            <div class="btn" data-toggle="modal" data-target=".bs-example-modal-center">
                                <img src="{{ url('/data_photo/' . Auth::user()->avatar) }}" alt=""
                                    class="avatar-lg mx-auto img-thumbnail rounded-circle">
                            </div>

                            <div class="mt-3 ">
                                <p class="text-dark font-weight-medium font-size-16">{{ Auth::user()->name }}</p>
                                <p class="text-body mb-1">{{ Auth::user()->asrama }}</p>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a href="/mentor-profile-info-edit/{{Auth::user()->id}}" class="btn btn-outline-primary" data-toggle="modal" data-target=".bs-info-modal-xl">
                            <i class="mdi mdi-account-edit-outline font-size-16"></i></a>
                    </div>
                    <br>
                    <br>
                    <h5 class="card-title mb-3">Personal Information</h5>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Email</p>
                        <h6 class="">{{ Auth::user()->email }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Tanggal Lahir</p>
                        <h6 class="">{{ date('d-m-Y', strtotime(Auth::user()->tgl_lahir))}}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Nomor Telepon</p>
                        <h6 class="">{{ Auth::user()->no_telp }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Alamat Asal</p>
                        <h6 class="">{{ Auth::user()->alamat }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kecamatan</p>
                        <h6 class="">{{ Auth::user()->kecamatan }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kota/Kabupaten</p>
                        <h6 class="">{{ Auth::user()->kota }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Provinsi</p>
                        <h6 class="">{{ Auth::user()->provinsi }}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kampus</p>
                        <h6 class="">{{ Auth::user()->universitas}}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Fakultas</p>
                        <h6 class="">{{ Auth::user()->fakultas}}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Program Studi</p>
                        <h6 class="">{{ Auth::user()->prodi}}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Organisasi</p>
                        <h6 class="">{{ Auth::user()->organisasi}}</h6>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-12 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>

                    <div style='clear:both'></div>
                </div>
            </div>
        </div>

    </div>

    <!-- end row -->
@endsection

@section('script')

    <!-- plugin js -->
    <script src="{{URL::asset('/libs/moment/moment.min.js')}}"></script>
    <script src="{{URL::asset('/libs/fullcalendar/fullcalendar.min.js')}}"></script>

    <!-- Calendar init -->
    <script src="{{URL::asset('/js/pages/calendar.init.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: new Date(),
                editable: true,
                eventLimit: true, // Allow "more" link when too many events,
                selectable: true,
                select: function (start, end) {
                    var title = prompt('Masukkan judul event:');
                    if (title) {
                        $('#calendar').fullCalendar('renderEvent', {
                            title: title,
                            start: start,
                            end: end,
                            allDay: false
                        }, true); // Tambahkan event ke kalender
                    }
                    $('#calendar').fullCalendar('unselect');
                },
                eventClick: function (event) {
                    var title = prompt('Ubah judul event:', event.title);
                    if (title) {
                        event.title = title;
                        $('#calendar').fullCalendar('updateEvent', event);
                    }
                },
                eventDrop: function (event, delta, revertFunc) {
                    // Saat event di-drop
                }
            });
        });
    </script>

@endsection
