@extends('super.layouts.master')

@section('title')
    Profile
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    @component('super.common-components.breadcrumb')
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

                {{--  Akademik Create  --}}
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-akademik-modal-xl" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Data Akademik Create</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-profile-akademik-store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="nama_warga">Nama</label>
                                                        <input type="text" class="form-control @error('nama_warga') is-invalid @enderror"
                                                            name="nama_warga" value="{{Auth::user()->name}}">
                                                    </div>
                                                    @error('nama_warga')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="asrama">Asrama</label>
                                                        <input type="text" class="form-control @error('asrama') is-invalid @enderror"
                                                            name="asrama" value="{{Auth::user()->asrama}}">
                                                    </div>
                                                    @error('asrama')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="komponen" class="mb-0 badge badge-soft-primary font-size-14">Komponen</label>
                                                        <select id="data_komponen_akademik" type="text" class="form-control @error('komponen') is-invalid @enderror"
                                                            name="komponen">
                                                            <option>- Pilih Komponen Kegiatan Akademik -</option>
                                                            @foreach ($komponen_akademiks as $item)
                                                                <option value="{{ $item->nama_komponen }}"
                                                                        data-kode="{{ $item->kode }}"
                                                                        data-id="{{ $item->id }}" 
                                                                        data-aspek="{{ $item->aspek }}">
                                                                    {{ $item->kode }} - {{ $item->nama_komponen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('komponen')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label>ID Komponen</label>
                                                        <input type="text" class="form-control" id="data" name="komponen_id"
                                                            readonly></input>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kegiatan">Nama Kegiatan</label>
                                                        <input type="text" class="form-control @error('kegiatan') is-invalid @enderror"
                                                            name="kegiatan">
                                                    </div>
                                                    @error('kegiatan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="waktu">Waktu Kegiatan</label>
                                                        <input type="date" class="form-control @error('waktu') is-invalid @enderror"
                                                            name="waktu">
                                                    </div>
                                                    @error('waktu')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tempat">Tempat Kegiatan</label>
                                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                                            name="tempat">
                                                    </div>
                                                    @error('tempat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="keterangan">Uraian Kegiatan</label>
                                                        <textarea rows="1" class="form-control @error('keterangan') is-invalid @enderror"
                                                            name="keterangan"></textarea>
                                                    </div>
                                                    @error('keterangan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file">file Kegiatan</label>
                                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                                            name="file">
                                                    </div>
                                                    @error('file')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
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

                {{--  Leadership Create  --}}
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-leadership-modal-xl" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Data Leadership Create</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-profile-leadership-store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="nama_warga">Nama</label>
                                                        <input type="text" class="form-control @error('nama_warga') is-invalid @enderror"
                                                            name="nama_warga" value="{{Auth::user()->name}}">
                                                    </div>
                                                    @error('nama_warga')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="asrama">Asrama</label>
                                                        <input type="text" class="form-control @error('asrama') is-invalid @enderror"
                                                            name="asrama" value="{{Auth::user()->asrama}}">
                                                    </div>
                                                    @error('asrama')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="komponen" class="mb-0 badge badge-soft-primary font-size-14">Komponen</label>
                                                        <select id="data_komponen_leadership" type="text" class="form-control @error('komponen') is-invalid @enderror"
                                                            name="komponen">
                                                            <option>- Pilih Komponen Kegiatan Leadership -</option>
                                                            @foreach ($komponen_leaderships as $item)
                                                                <option value="{{ $item->nama_komponen }}"
                                                                        data-kode="{{ $item->kode }}"
                                                                        data_leadership-id="{{ $item->id }}" 
                                                                        data-aspek="{{ $item->aspek }}">
                                                                    {{ $item->kode }} - {{ $item->nama_komponen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('komponen')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label>ID Komponen</label>
                                                        <input type="text" class="form-control" id="data_leadership" name="komponen_id"
                                                            readonly></input>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kegiatan">Nama Kegiatan</label>
                                                        <input type="text" class="form-control @error('kegiatan') is-invalid @enderror"
                                                            name="kegiatan">
                                                    </div>
                                                    @error('kegiatan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="waktu">Waktu Kegiatan</label>
                                                        <input type="date" class="form-control @error('waktu') is-invalid @enderror"
                                                            name="waktu">
                                                    </div>
                                                    @error('waktu')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tempat">Tempat Kegiatan</label>
                                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                                            name="tempat">
                                                    </div>
                                                    @error('tempat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="keterangan">Uraian Kegiatan</label>
                                                        <textarea rows="1" class="form-control @error('keterangan') is-invalid @enderror"
                                                            name="keterangan"></textarea>
                                                    </div>
                                                    @error('keterangan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file">file Kegiatan</label>
                                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                                            name="file">
                                                    </div>
                                                    @error('file')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
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

                {{--  Karakter Create  --}}
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-karakter-modal-xl" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Data Karakter Islami Create</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-profile-karakter-store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="nama_warga">Nama</label>
                                                        <input type="text" class="form-control @error('nama_warga') is-invalid @enderror"
                                                            name="nama_warga" value="{{Auth::user()->name}}">
                                                    </div>
                                                    @error('nama_warga')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="asrama">Asrama</label>
                                                        <input type="text" class="form-control @error('asrama') is-invalid @enderror"
                                                            name="asrama" value="{{Auth::user()->asrama}}">
                                                    </div>
                                                    @error('asrama')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="komponen" class="mb-0 badge badge-soft-primary font-size-14">Komponen</label>
                                                        <select id="data_komponen_karakter" type="text" class="form-control @error('komponen') is-invalid @enderror"
                                                            name="komponen">
                                                            <option>- Pilih Komponen Kegiatan Karakter Islami -</option>
                                                            @foreach ($komponen_karakters as $item)
                                                                <option value="{{ $item->nama_komponen }}"
                                                                        data-kode="{{ $item->kode }}"
                                                                        data_karakter-id="{{ $item->id }}" 
                                                                        data-aspek="{{ $item->aspek }}">
                                                                    {{ $item->kode }} - {{ $item->nama_komponen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('komponen')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label>ID Komponen</label>
                                                        <input type="text" class="form-control" id="data_karakter" name="komponen_id"
                                                            readonly></input>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kegiatan">Nama Kegiatan</label>
                                                        <input type="text" class="form-control @error('kegiatan') is-invalid @enderror"
                                                            name="kegiatan">
                                                    </div>
                                                    @error('kegiatan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="waktu">Waktu Kegiatan</label>
                                                        <input type="date" class="form-control @error('waktu') is-invalid @enderror"
                                                            name="waktu">
                                                    </div>
                                                    @error('waktu')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tempat">Tempat Kegiatan</label>
                                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                                            name="tempat">
                                                    </div>
                                                    @error('tempat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="keterangan">Uraian Kegiatan</label>
                                                        <textarea rows="1" class="form-control @error('keterangan') is-invalid @enderror"
                                                            name="keterangan"></textarea>
                                                    </div>
                                                    @error('keterangan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file">file Kegiatan</label>
                                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                                            name="file">
                                                    </div>
                                                    @error('file')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
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

                {{--  Kreatif Create  --}}
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-kreatif-modal-xl" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Data Kreativitas Create</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-profile-kreatif-store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="nama_warga">Nama</label>
                                                        <input type="text" class="form-control @error('nama_warga') is-invalid @enderror"
                                                            name="nama_warga" value="{{Auth::user()->name}}">
                                                    </div>
                                                    @error('nama_warga')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label for="asrama">Asrama</label>
                                                        <input type="text" class="form-control @error('asrama') is-invalid @enderror"
                                                            name="asrama" value="{{Auth::user()->asrama}}">
                                                    </div>
                                                    @error('asrama')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="komponen" class="mb-0 badge badge-soft-primary font-size-14">Komponen</label>
                                                        <select id="data_komponen_kreatif" type="text" class="form-control @error('komponen') is-invalid @enderror"
                                                            name="komponen">
                                                            <option>- Pilih Komponen Kegiatan Kreativitas -</option>
                                                            @foreach ($komponen_kreatifs as $item)
                                                                <option value="{{ $item->nama_komponen }}"
                                                                        data-kode="{{ $item->kode }}"
                                                                        data_kreatif-id="{{ $item->id }}" 
                                                                        data-aspek="{{ $item->aspek }}">
                                                                    {{ $item->kode }} - {{ $item->nama_komponen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('komponen')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6" hidden>
                                                    <div class="form-group">
                                                        <label>ID Komponen</label>
                                                        <input type="text" class="form-control" id="data_kreatif" name="komponen_id"
                                                            readonly></input>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kegiatan">Nama Kegiatan</label>
                                                        <input type="text" class="form-control @error('kegiatan') is-invalid @enderror"
                                                            name="kegiatan">
                                                    </div>
                                                    @error('kegiatan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="waktu">Waktu Kegiatan</label>
                                                        <input type="date" class="form-control @error('waktu') is-invalid @enderror"
                                                            name="waktu">
                                                    </div>
                                                    @error('waktu')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tempat">Tempat Kegiatan</label>
                                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                                            name="tempat">
                                                    </div>
                                                    @error('tempat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="keterangan">Uraian Kegiatan</label>
                                                        <textarea rows="1" class="form-control @error('keterangan') is-invalid @enderror"
                                                            name="keterangan"></textarea>
                                                    </div>
                                                    @error('keterangan')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file">file Kegiatan</label>
                                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                                            name="file">
                                                    </div>
                                                    @error('file')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
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
                                <form action="/super-profile-foto-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
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
                                <form action="/super-profile-info-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
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

                {{--  Status Warga  --}}

                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-status-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Update Status Warga</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-profile-status-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="form-group col-6">
                                                <label for="status_warga" class="control-label">Status Warga Asrama:</label>
                                                    <select name="status_warga" class="form-control">
                                                        <option>- Pilih Status Warga -</option>
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

                {{--  index prestasi semester  --}}
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-ips-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Index Prestasi Semester Create</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/super-ipk-store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="semester">Semester:</label>
                                                    <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                                        name="semester">
                                                </div>
                                                @error('semester')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tahun">Tahun:</label>
                                                    <input type="text" class="form-control @error('tahun') is-invalid @enderror"
                                                        name="tahun">
                                                </div>
                                                @error('tahun')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ip">IP:</label>
                                                    <input type="text" class="form-control @error('tahun') is-invalid @enderror"
                                                        name="ip">
                                                </div>
                                                @error('ip')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="file">File KHS: <span style="color: red">Support PDF aja!</span></label>
                                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                                        name="file">
                                                </div>
                                                @error('file')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
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
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Status Warga</p>
                            <label class="mb-0 badge badge-soft-info font-size-14">{{ Auth::user()->status_warga}}</label>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <div>
                                    <a href="/super-profile-status-edit/{{Auth::user()->id}}" class="btn btn-outline-primary" data-toggle="modal" data-target=".bs-status-modal-center">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a href="/super-profile-info-edit/{{Auth::user()->id}}" class="btn btn-outline-primary" data-toggle="modal" data-target=".bs-info-modal-xl">
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
            <div class="row">
                <div class="col-md-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">IPK</p>
                                    <h4 class="mb-0 badge badge-soft-success font-size-14">{{$data_ipks}}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            {{--  <i class="mdi mdi-arrow-up text-success ml-1"></i>  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Nomor Induk Warga</p>
                                    <label class="mb-0 badge badge-soft-primary font-size-14">{{Auth::user()->no_induk}}</label>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            {{--  <i class="mdi mdi-arrow-up text-success ml-1"></i>  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Mentor</p>
                                    <label class="mb-0 badge badge-soft-primary font-size-14">-</label>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            {{--  <i class="mdi mdi-arrow-up text-success ml-1"></i>  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Data Statistik Keseluruhan</h4>

                            <div id="statistik_total" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#akademik" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-school-outline"></i></span>
                                <span class="d-none d-sm-block">Akademik</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#leadership" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account-tie-outline"></i></span>
                                <span class="d-none d-sm-block">Leadership</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#karakter" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-islam"></i></span>
                                <span class="d-none d-sm-block">Karakter Islami</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kreatif" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-lightbulb-on-outline"></i></span>
                                <span class="d-none d-sm-block">Kreativitas & Kewirausahaan</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3 text-muted">
                        <!-- Tab akademik -->
                        <div class="tab-pane active" id="akademik" role="tabpanel">
                            <h4 class="card-title mb-4">Data Statistik Komponen Akademik</h4>
                            <div id="akademik_chart" class="apex-charts mt-4"></div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a type="button" class="btn btn-rounded btn-success" data-toggle="modal" data-target=".bs-akademik-modal-xl">
                                                <span style="color: white"><i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data Akademik </span>
                                            </a>
                                        </div>
                                        <br>
                                        <div class="panel-heading"><h5>Data Kegiatan Akademik</h5></div>
                                        <br>
                                            <div class="panel-body">
                                                <div style="overflow: scroll">
                                                    <table class="table table-condensed" style="border-collapse:collapse;">
                                                    
                                                        <thead>
                                                            <tr><th>&nbsp;</th>
                                                                <th><strong>Kode</strong></th>
                                                                <th><strong>Komponen</strong></th>
                                                                <th><strong>Jumlah</strong></th>
                                                                <th><strong>Presentase</strong></th>
                                                               
                                                            </tr>
                                                        </thead>
                                                    
                                                        <tbody>
                                                            <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>1001</td>
                                                                    <td>Mendapatkan nilai (prestasi) akademik</td>
                                                                    <td>{{$kom1_akademiks_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo1"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom1_akademiks as $data_kom1)    
                                                                                <tr>
                                                                                    <td>{{$data_kom1->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom1->waktu))}}</td>
                                                                                    <td>{{$data_kom1->tempat}}</td>
                                                                                    <td>{{$data_kom1->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>

                                                            <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>1002</td>
                                                                    <td>Mengikuti kegiatan mentoring</td>
                                                                    <td>{{$kom2_akademiks_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo2"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom2_akademiks as $data_kom2)    
                                                                                <tr>
                                                                                    <td>{{$data_kom2->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom2->waktu))}}</td>
                                                                                    <td>{{$data_kom2->tempat}}</td>
                                                                                    <td>{{$data_kom2->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1003</td>
                                                                <td>Mengikuti forum akademik</td>
                                                                <td>{{$kom3_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo3"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom3_akademiks as $data_kom3)    
                                                                                <tr>
                                                                                    <td>{{$data_kom3->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom3->waktu))}}</td>
                                                                                    <td>{{$data_kom3->tempat}}</td>
                                                                                    <td>{{$data_kom3->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo4" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1004</td>
                                                                <td>Membaca buku atau artikel dll</td>
                                                                <td>{{$kom4_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo4"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom4_akademiks as $data_kom4)    
                                                                                <tr>
                                                                                    <td>{{$data_kom4->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom4->waktu))}}</td>
                                                                                    <td>{{$data_kom4->tempat}}</td>
                                                                                    <td>{{$data_kom4->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo5" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1005</td>
                                                                <td>Memanfaatkan TIK untuk pengembangan diri</td>
                                                                <td>{{$kom5_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo5"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom5_akademiks as $data_kom5)    
                                                                                <tr>
                                                                                    <td>{{$data_kom5->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom5->waktu))}}</td>
                                                                                    <td>{{$data_kom5->tempat}}</td>
                                                                                    <td>{{$data_kom5->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo6" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1006</td>
                                                                <td>Menulis makalah, artikel dll</td>
                                                                <td>{{$kom6_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo6"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom6_akademiks as $data_kom6)    
                                                                                <tr>
                                                                                    <td>{{$data_kom6->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom6->waktu))}}</td>
                                                                                    <td>{{$data_kom6->tempat}}</td>
                                                                                    <td>{{$data_kom6->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo7" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1007</td>
                                                                <td>Menyampaikan gagasan, presentasi, moderator</td>
                                                                <td>{{$kom7_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo7"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom7_akademiks as $data_kom7)    
                                                                                <tr>
                                                                                    <td>{{$data_kom7->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom7->waktu))}}</td>
                                                                                    <td>{{$data_kom7->tempat}}</td>
                                                                                    <td>{{$data_kom7->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>1008</td>
                                                                <td>Memberikan kontribusi (mengajar, melatih,membimbing)</td>
                                                                <td>{{$kom8_akademiks_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo8"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom8_akademiks as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
            
                                    </div> 
            
                                </div>
                                
                            </div>
                        </div>

                        <!-- Tab Leadership -->
                        <div class="tab-pane" id="leadership" role="tabpanel">
                            <h4 class="card-title mb-4">Data Statistik Komponen Leadership</h4>
                            <div id="leadership_chart" class="apex-charts mt-4"></div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a type="button" class="btn btn-rounded btn-success" data-toggle="modal" data-target=".bs-leadership-modal-xl">
                                                <span style="color: white"><i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data Leadership </span>
                                            </a>
                                        </div>
                                        <br>
                                        <div class="panel-heading"><h5>Data Kegiatan Leadership</h5></div>
                                        <br>
                                            <div class="panel-body">
                                                <div style="overflow: scroll">
                                                    <table class="table table-condensed" style="border-collapse:collapse;">
                                                    
                                                        <thead>
                                                            <tr><th>&nbsp;</th>
                                                                <th><strong>Kode</strong></th>
                                                                <th><strong>Komponen</strong></th>
                                                                <th><strong>Jumlah</strong></th>
                                                                <th><strong>Presentase</strong></th>
                                                               
                                                            </tr>
                                                        </thead>
                                                    
                                                        <tbody>
                                                            <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>2001</td>
                                                                    <td>Mengikuti pelatihan kepemimpinan</td>
                                                                    <td>{{$kom1_leaderships_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo1"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom1_leaderships as $data_kom1)    
                                                                                <tr>
                                                                                    <td>{{$data_kom1->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom1->waktu))}}</td>
                                                                                    <td>{{$data_kom1->tempat}}</td>
                                                                                    <td>{{$data_kom1->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>

                                                            <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>2002</td>
                                                                    <td>Mengikuti kegiatan mentoring</td>
                                                                    <td>{{$kom2_leaderships_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo2"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom2_leaderships as $data_kom2)    
                                                                                <tr>
                                                                                    <td>{{$data_kom2->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom2->waktu))}}</td>
                                                                                    <td>{{$data_kom2->tempat}}</td>
                                                                                    <td>{{$data_kom2->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2003</td>
                                                                <td>Melaksanakan tugas kepanitiaan (mandat)</td>
                                                                <td>{{$kom3_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo3"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom3_leaderships as $data_kom3)    
                                                                                <tr>
                                                                                    <td>{{$data_kom3->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom3->waktu))}}</td>
                                                                                    <td>{{$data_kom3->tempat}}</td>
                                                                                    <td>{{$data_kom3->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo4" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2004</td>
                                                                <td>Melakukan tugas sebagai pengurus organisasi</td>
                                                                <td>{{$kom4_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo4"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom4_leaderships as $data_kom4)    
                                                                                <tr>
                                                                                    <td>{{$data_kom4->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom4->waktu))}}</td>
                                                                                    <td>{{$data_kom4->tempat}}</td>
                                                                                    <td>{{$data_kom4->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo5" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2005</td>
                                                                <td>Menjadi peserta atau memimpin rapat</td>
                                                                <td>{{$kom5_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo5"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom5_leaderships as $data_kom5)    
                                                                                <tr>
                                                                                    <td>{{$data_kom5->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom5->waktu))}}</td>
                                                                                    <td>{{$data_kom5->tempat}}</td>
                                                                                    <td>{{$data_kom5->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo6" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2006</td>
                                                                <td>Mengikuti diskusi atau debat penyelesaian masalah</td>
                                                                <td>{{$kom6_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo6"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom6_leaderships as $data_kom6)    
                                                                                <tr>
                                                                                    <td>{{$data_kom6->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom6->waktu))}}</td>
                                                                                    <td>{{$data_kom6->tempat}}</td>
                                                                                    <td>{{$data_kom6->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo7" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2007</td>
                                                                <td>Menulis surat, proposal kegiatan, laporan dll</td>
                                                                <td>{{$kom7_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo7"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom7_leaderships as $data_kom7)    
                                                                                <tr>
                                                                                    <td>{{$data_kom7->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom7->waktu))}}</td>
                                                                                    <td>{{$data_kom7->tempat}}</td>
                                                                                    <td>{{$data_kom7->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2008</td>
                                                                <td>Memberikan kontribusi baik harta, tenaga, waktu</td>
                                                                <td>{{$kom8_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo8"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom8_leaderships as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>2009</td>
                                                                <td>Menyampaikan gagasan baik lisan atau tulisan</td>
                                                                <td>{{$kom9_leaderships_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo9"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom9_leaderships as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
            
                                    </div> 
            
                                </div>
                                
                            </div>
                        </div>

                        <!-- Tab Karakter -->
                        <div class="tab-pane" id="karakter" role="tabpanel">
                            <h4 class="card-title mb-4">Data Statistik Komponen Karakter Islami</h4>
                            <div id="karakter_chart" class="apex_chart mt-4"></div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a type="button" class="btn btn-rounded btn-success" data-toggle="modal" data-target=".bs-karakter-modal-xl">
                                                <span style="color: white"><i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data Karakter Islami</span>
                                            </a>
                                        </div>
                                        <br>
                                        <div class="panel-heading"><h5>Data Kegiatan Karakter Islami</h5></div>
                                        <br>
                                            <div class="panel-body">
                                                <div style="overflow: scroll">
                                                    <table class="table table-condensed" style="border-collapse:collapse;">
                                                    
                                                        <thead>
                                                            <tr><th>&nbsp;</th>
                                                                <th><strong>Kode</strong></th>
                                                                <th><strong>Komponen</strong></th>
                                                                <th><strong>Jumlah</strong></th>
                                                                <th><strong>Presentase</strong></th>
                                                               
                                                            </tr>
                                                        </thead>
                                                    
                                                        <tbody>
                                                            <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>3001</td>
                                                                    <td>Membaca Al Quran, hafalan, hadits pilihan</td>
                                                                    <td>{{$kom1_karakters_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo1"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom1_karakters as $data_kom1)    
                                                                                <tr>
                                                                                    <td>{{$data_kom1->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom1->waktu))}}</td>
                                                                                    <td>{{$data_kom1->tempat}}</td>
                                                                                    <td>{{$data_kom1->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>

                                                            <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>3002</td>
                                                                    <td>Mengikuti kegiatan mentoring</td>
                                                                    <td>{{$kom2_karakters_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo2"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom2_karakters as $data_kom2)    
                                                                                <tr>
                                                                                    <td>{{$data_kom2->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom2->waktu))}}</td>
                                                                                    <td>{{$data_kom2->tempat}}</td>
                                                                                    <td>{{$data_kom2->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3003</td>
                                                                <td>Mengikuti kajian, membaca buku atau ceramah agama</td>
                                                                <td>{{$kom3_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo3"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom3_karakters as $data_kom3)    
                                                                                <tr>
                                                                                    <td>{{$data_kom3->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom3->waktu))}}</td>
                                                                                    <td>{{$data_kom3->tempat}}</td>
                                                                                    <td>{{$data_kom3->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo4" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3004</td>
                                                                <td>Menjadi imam shalat jamaah atau memimpin doa</td>
                                                                <td>{{$kom4_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo4"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom4_karakters as $data_kom4)    
                                                                                <tr>
                                                                                    <td>{{$data_kom4->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom4->waktu))}}</td>
                                                                                    <td>{{$data_kom4->tempat}}</td>
                                                                                    <td>{{$data_kom4->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo5" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3005</td>
                                                                <td>Mengamalkan ibadah harian; shalat, puasa, zakat, dll</td>
                                                                <td>{{$kom5_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo5"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom5_karakters as $data_kom5)    
                                                                                <tr>
                                                                                    <td>{{$data_kom5->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom5->waktu))}}</td>
                                                                                    <td>{{$data_kom5->tempat}}</td>
                                                                                    <td>{{$data_kom5->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo6" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3006</td>
                                                                <td>Menyampaikan dakwah, kultum, baik lisan, tulisan</td>
                                                                <td>{{$kom6_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo6"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom6_karakters as $data_kom6)    
                                                                                <tr>
                                                                                    <td>{{$data_kom6->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom6->waktu))}}</td>
                                                                                    <td>{{$data_kom6->tempat}}</td>
                                                                                    <td>{{$data_kom6->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo7" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3007</td>
                                                                <td>Memelihara kebersihan (kamar, lingkungan, dll)</td>
                                                                <td>{{$kom7_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo7"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom7_karakters as $data_kom7)    
                                                                                <tr>
                                                                                    <td>{{$data_kom7->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom7->waktu))}}</td>
                                                                                    <td>{{$data_kom7->tempat}}</td>
                                                                                    <td>{{$data_kom7->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3008</td>
                                                                <td>Mengajar pengajian, TPA, TPQ, dll</td>
                                                                <td>{{$kom8_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo8"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom8_karakters as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>3009</td>
                                                                <td>Memelihara silaturahmi dan menolong sesama</td>
                                                                <td>{{$kom9_karakters_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo9"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom9_karakters as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
            
                                    </div> 
            
                                </div>
                                
                            </div>
                        </div>

                        <!-- Tab kreatif -->
                        <div class="tab-pane" id="kreatif" role="tabpanel">
                            <h4 class="card-title mb-4">Data Statistik Komponen Kreativitas & Kewirausahaan</h4>
                            <div id="kreatif_chart" class="apex_chart mt-4"></div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a type="button" class="btn btn-rounded btn-success" data-toggle="modal" data-target=".bs-kreatif-modal-xl">
                                                <span style="color: white"><i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i>Add Data Kreativitas</span>
                                            </a>
                                        </div>
                                        <br>
                                        <div class="panel-heading"><h5>Data Kegiatan Kreativitas & Kewirausahaan</h5></div>
                                        <br>
                                            <div class="panel-body">
                                                <div style="overflow: scroll">
                                                    <table class="table table-condensed" style="border-collapse:collapse;">
                                                    
                                                        <thead>
                                                            <tr><th>&nbsp;</th>
                                                                <th><strong>Kode</strong></th>
                                                                <th><strong>Komponen</strong></th>
                                                                <th><strong>Jumlah</strong></th>
                                                                <th><strong>Presentase</strong></th>
                                                               
                                                            </tr>
                                                        </thead>
                                                    
                                                        <tbody>
                                                            <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>4001</td>
                                                                    <td>Mengikuti pelatihan kreativitas dan kewirausahaan</td>
                                                                    <td>{{$kom1_kreatifs_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo1"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom1_kreatifs as $data_kom1)    
                                                                                <tr>
                                                                                    <td>{{$data_kom1->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom1->waktu))}}</td>
                                                                                    <td>{{$data_kom1->tempat}}</td>
                                                                                    <td>{{$data_kom1->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>

                                                            <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                                                    <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                    <td>4002</td>
                                                                    <td>Mengikuti kegiatan mentoring</td>
                                                                    <td>{{$kom2_kreatifs_count}}</td>
                                                                    <td>0%</td>
                                                                    
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo2"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom2_kreatifs as $data_kom2)    
                                                                                <tr>
                                                                                    <td>{{$data_kom2->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom2->waktu))}}</td>
                                                                                    <td>{{$data_kom2->tempat}}</td>
                                                                                    <td>{{$data_kom2->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4003</td>
                                                                <td>Membaca buku, majalah, internet dll terkait kewirausahaan</td>
                                                                <td>{{$kom3_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo3"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom3_kreatifs as $data_kom3)    
                                                                                <tr>
                                                                                    <td>{{$data_kom3->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom3->waktu))}}</td>
                                                                                    <td>{{$data_kom3->tempat}}</td>
                                                                                    <td>{{$data_kom3->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo4" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4004</td>
                                                                <td>Mengikuti forum ceramah atau diskusi kewirausahaan</td>
                                                                <td>{{$kom4_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo4"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom4_kreatifs as $data_kom4)    
                                                                                <tr>
                                                                                    <td>{{$data_kom4->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom4->waktu))}}</td>
                                                                                    <td>{{$data_kom4->tempat}}</td>
                                                                                    <td>{{$data_kom4->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo5" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4005</td>
                                                                <td>Melakukan tugas dalam kegiatan usaha asrama</td>
                                                                <td>{{$kom5_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo5"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom5_kreatifs as $data_kom5)    
                                                                                <tr>
                                                                                    <td>{{$data_kom5->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom5->waktu))}}</td>
                                                                                    <td>{{$data_kom5->tempat}}</td>
                                                                                    <td>{{$data_kom5->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo6" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4006</td>
                                                                <td>Menulis proposal usaha</td>
                                                                <td>{{$kom6_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo6"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom6_kreatifs as $data_kom6)    
                                                                                <tr>
                                                                                    <td>{{$data_kom6->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom6->waktu))}}</td>
                                                                                    <td>{{$data_kom6->tempat}}</td>
                                                                                    <td>{{$data_kom6->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo7" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4007</td>
                                                                <td>Menghasilkan karya kreatif (video, grafis, dll)</td>
                                                                <td>{{$kom7_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo7"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom7_kreatifs as $data_kom7)    
                                                                                <tr>
                                                                                    <td>{{$data_kom7->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom7->waktu))}}</td>
                                                                                    <td>{{$data_kom7->tempat}}</td>
                                                                                    <td>{{$data_kom7->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                            <tr data-toggle="collapse" data-target="#demo8" class="accordion-toggle">
                                                                <td><button class="btn btn-default btn-xs"><span class="mdi mdi-format-list-bulleted-square"></span></button></td>
                                                                <td>4008</td>
                                                                <td>Memiliki keberanian untuk memulai usaha</td>
                                                                <td>{{$kom8_kreatifs_count}}</td>
                                                                <td>0%</td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow">
                                                                    <div class="accordian-body collapse" id="demo8"> 
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Waktu</th>
                                                                                    <th>Tempat</th>
                                                                                    <th>Uraian Kegiatan</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($kom8_kreatifs as $data_kom8)    
                                                                                <tr>
                                                                                    <td>{{$data_kom8->kegiatan}}</td>
                                                                                    <td>{{date('d-m-Y', strtotime($data_kom8->waktu))}}</td>
                                                                                    <td>{{$data_kom8->tempat}}</td>
                                                                                    <td>{{$data_kom8->keterangan}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
            
                                    </div> 
            
                                </div>
                                
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a href="/super-profile-ips-create" class="btn btn-outline-success" data-toggle="modal" data-target=".bs-ips-modal-center">
                            <i class="mdi mdi-plus-outline font-size-16"></i> Add Data IP</a>
                    </div>
                    <br>
                    <br>
                    <h4 class="card-title mb-4">Data Index Prestasi Akademik per Semester</h4>

                    <div style="overflow: scroll">
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Semester ke-</th>
                                        <th scope="col">Tahun</th>
                                        <th scope="col">IP</th>
                                        <th scope="col" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ipks as $item)
                                        
                                    <tr>
                                        <td>{{$item->semester}}</td>
                                        <td>{{$item->tahun}}</td>
                                        <td>{{$item->ip}}</td>
                                        <td>
                                            <a href="/super-ipk-detail/{{$item->id}}" class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-eye-circle-outline"></i> View</a>
                                            <a href="/super-ipk-edit/{{$item->id}}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="mdi mdi-file-edit-outline"></i> Edit</a>
                                            <a href="/super-ipk-khs-edit/{{$item->id}}" class="btn btn-outline-info btn-sm">
                                                    <i class="mdi mdi-image-edit-outline"></i> Update KHS</a>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-print-none">
                <div class="float-right">
                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                            class="fa fa-print"></i> Print</a>
                </div>
            </div>
        </div>


    </div>

    <!-- end row -->
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

    <script>
        // pie chart total
        var options = {
            chart: {
                height: 320,
                type: 'pie',
            },
            series: [{{ $akademiks }}, {{ $leaderships }}, {{ $karakters }}, {{ $kreatifs }}],
            labels: ["Akademik", "Leadership", "Karakter Islami", "Kreativitas & Kewirausahaan"],
            colors: ["#45cb85", "#3b5de7", "#ff715b", "#eeb902"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#statistik_total"),
            options
        );

        chart.render();
    </script>

    <script>
        // Donut chart Akademik

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_akademiks_count}}, {{$kom2_akademiks_count}}, {{$kom3_akademiks_count}}, {{$kom4_akademiks_count}}, {{$kom5_akademiks_count}}, {{$kom6_akademiks_count}}, {{$kom7_akademiks_count}}, {{$kom8_akademiks_count}}],
            labels: ["1001", "1002", "1003", "1004", "1005", "1006", "1007", "1008"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#akademik_chart"),
            options
        );
        
        chart.render();
    </script>

    <script>
        // Donut chart Leadership

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_leaderships_count}}, {{$kom2_leaderships_count}}, {{$kom3_leaderships_count}}, {{$kom4_leaderships_count}}, {{$kom5_leaderships_count}}, {{$kom6_leaderships_count}}, {{$kom7_leaderships_count}}, {{$kom8_leaderships_count}}, {{$kom9_leaderships_count}}],
            labels: ["2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2", "#030836"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#leadership_chart"),
            options
        );
        
        chart.render();
    </script>
    <script>
        // Donut chart Karakter Islami

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_karakters_count}}, {{$kom2_karakters_count}}, {{$kom3_karakters_count}}, {{$kom4_karakters_count}}, {{$kom5_karakters_count}}, {{$kom6_karakters_count}}, {{$kom7_karakters_count}}, {{$kom8_karakters_count}}, {{$kom9_karakters_count}}],
            labels: ["3001", "3002", "3003", "3004", "3005", "3006", "3007", "3008", "3009"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2", "#030836"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#karakter_chart"),
            options
        );
        
        chart.render();
    </script>
    <script>
        // Donut chart Kreatifitas

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_kreatifs_count}}, {{$kom2_kreatifs_count}}, {{$kom3_kreatifs_count}}, {{$kom4_kreatifs_count}}, {{$kom5_kreatifs_count}}, {{$kom6_kreatifs_count}}, {{$kom7_kreatifs_count}}, {{$kom8_kreatifs_count}}],
            labels: ["4001", "4002", "4003", "4004", "4005", "4006", "4007", "4008"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#kreatif_chart"),
            options
        );
        
        chart.render();
    </script>

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
        // Ambil dari atribut data komponen akademik
        $(document).ready(function() {
            $('#data_komponen_akademik').select2();
            $('#data_komponen_akademik').on('change', function() {
                const selected = $(this).find('option:selected');
                const jum = selected.data('id');

                $("#data").val(jum);
            });
        });
    </script>

    <script>
        // Ambil dari atribut data komponen leadership
        $(document).ready(function() {
            $('#data_komponen_leadership').select2();
            $('#data_komponen_leadership').on('change', function() {
                const selected = $(this).find('option:selected');
                const jum = selected.data('id');

                $("#data_leadership").val(jum);
            });
        });
    </script>

    <script>
        // Ambil dari atribut data komponen Karakter
        $(document).ready(function() {
            $('#data_komponen_karakter').select2();
            $('#data_komponen_karakter').on('change', function() {
                const selected = $(this).find('option:selected');
                const jum = selected.data('id');

                $("#data_karakter").val(jum);
            });
        });
    </script>

    <script>
        // Ambil dari atribut data komponen Kreatif
        $(document).ready(function() {
            $('#data_komponen_kreatif').select2();
            $('#data_komponen_kreatif').on('change', function() {
                const selected = $(this).find('option:selected');
                const jum = selected.data('id');

                $("#data_kreatif").val(jum);
            });
        });
    </script>

@endsection
