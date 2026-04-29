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
Dashboard
@endslot
@slot('li_1')
Pages
@endslot
@endcomponent

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
            @if(session()->has('info-nama'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Edit!</strong> Data Nama berhasil diperbarui.
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <h6>Akademik</h6>
                                            <h4>{{ $akademiks }}</h4>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <a href="/mahasiswa-data-akademik-create" class="btn btn-primary">Add Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <h6>Leadership</h6>
                                            <h4>{{ $leaderships }}</h4>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <a href="/mahasiswa-data-leadership-create" class="btn btn-success">Add Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <h6>Karakter</h6>
                                            <h4>{{ $karakters }}</h4>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <a href="/mahasiswa-data-karakter-create" class="btn btn-info">Add Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <h6>Kreatifitas</h6>
                                            <h4>{{ $kreatifs }}</h4>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <a href="/mahasiswa-data-kreatif-create" class="btn btn-warning">Add Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3"><strong>FILTER JUMLAH KEGIATAN</strong></div>
                    <form action="{{ route('mahasiswa-filterDate') }}" method="GET">
                        <div style="background-color: rgba(200, 200, 255, 0.249)"
                            class="row justify-content-center pt-3 pb-3">
                            <div class="col-md-3 col-6">
                                <label for="startDate">Tanggal Awal</label>
                                <input type="text" name="startDate" id="startDate" class="form-control datepicker"
                                    placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="endDate">Tanggal Akhir</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker"
                                    placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-1 col-12 mt-2 align-self-end">
                                <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <h5>Data Kegiatan Akademik</h5>
                                        </div>
                                        <br>
                                        <div class="panel-body">
                                            <div style="overflow: scroll">
                                                <table class="table table-condensed" style="border-collapse:collapse;">

                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th><strong>Kode</strong></th>
                                                            <th><strong>Komponen</strong></th>
                                                            <th><strong>Jumlah</strong></th>
                                                            <th><strong>Presentase</strong></th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr data-toggle="collapse" data-target="#demo1"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom1->id}}">
                                                                                        {{$data_kom1->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom1->waktu))}}</td>
                                                                                <td>{{$data_kom1->tempat}}</td>
                                                                                <td>{{$data_kom1->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom1->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom1->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom1->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom1->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom1->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom1->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo2"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom2->id}}">
                                                                                        {{$data_kom2->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom2->waktu))}}</td>
                                                                                <td>{{$data_kom2->tempat}}</td>
                                                                                <td>{{$data_kom2->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom2->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom2->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom2->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom2->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom2->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom2->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo3"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom3->id}}">
                                                                                        {{$data_kom3->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom3->waktu))}}</td>
                                                                                <td>{{$data_kom3->tempat}}</td>
                                                                                <td>{{$data_kom3->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom3->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom3->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom3->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom3->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom3->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom3->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo4"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom4->id}}">
                                                                                        {{$data_kom4->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom4->waktu))}}</td>
                                                                                <td>{{$data_kom4->tempat}}</td>
                                                                                <td>{{$data_kom4->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom4->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom4->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom4->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom4->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom4->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom4->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo5"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom5->id}}">
                                                                                        {{$data_kom5->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom5->waktu))}}</td>
                                                                                <td>{{$data_kom5->tempat}}</td>
                                                                                <td>{{$data_kom5->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom5->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom5->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom5->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom5->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom5->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom5->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo6"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom6->id}}">
                                                                                        {{$data_kom6->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom6->waktu))}}</td>
                                                                                <td>{{$data_kom6->tempat}}</td>
                                                                                <td>{{$data_kom6->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom6->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom6->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom6->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom6->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom6->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom6->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo7"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom7->id}}">
                                                                                        {{$data_kom7->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom7->waktu))}}</td>
                                                                                <td>{{$data_kom7->tempat}}</td>
                                                                                <td>{{$data_kom7->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom7->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom7->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom7->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom7->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom7->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom7->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo8"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom8->id}}">
                                                                                        {{$data_kom8->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom8->waktu))}}</td>
                                                                                <td>{{$data_kom8->tempat}}</td>
                                                                                <td>{{$data_kom8->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom8->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom8->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom8->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom8->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom8->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_akademik/' . $data_kom8->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <h5>Data Kegiatan Leadership</h5>
                                        </div>
                                        <br>
                                        <div class="panel-body">
                                            <div style="overflow: scroll">
                                                <table class="table table-condensed" style="border-collapse:collapse;">

                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th><strong>Kode</strong></th>
                                                            <th><strong>Komponen</strong></th>
                                                            <th><strong>Jumlah</strong></th>
                                                            <th><strong>Presentase</strong></th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr data-toggle="collapse" data-target="#demo1"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom1->id}}">
                                                                                        {{$data_kom1->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom1->waktu))}}</td>
                                                                                <td>{{$data_kom1->tempat}}</td>
                                                                                <td>{{$data_kom1->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom1->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom1->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom1->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom1->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom1->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom1->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo2"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom2->id}}">
                                                                                        {{$data_kom2->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom2->waktu))}}</td>
                                                                                <td>{{$data_kom2->tempat}}</td>
                                                                                <td>{{$data_kom2->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom2->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom2->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom2->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom2->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom2->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom2->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo3"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom3->id}}">
                                                                                        {{$data_kom3->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom3->waktu))}}</td>
                                                                                <td>{{$data_kom3->tempat}}</td>
                                                                                <td>{{$data_kom3->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom3->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom3->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom3->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom3->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom3->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom3->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo4"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom4->id}}">
                                                                                        {{$data_kom4->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom4->waktu))}}</td>
                                                                                <td>{{$data_kom4->tempat}}</td>
                                                                                <td>{{$data_kom4->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom4->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom4->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom4->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom4->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom4->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom4->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo5"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom5->id}}">
                                                                                        {{$data_kom5->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom5->waktu))}}</td>
                                                                                <td>{{$data_kom5->tempat}}</td>
                                                                                <td>{{$data_kom5->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom5->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom5->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom5->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom5->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom5->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom5->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo6"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom6->id}}">
                                                                                        {{$data_kom6->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom6->waktu))}}</td>
                                                                                <td>{{$data_kom6->tempat}}</td>
                                                                                <td>{{$data_kom6->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom6->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom6->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom6->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom6->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom6->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom6->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo7"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom7->id}}">
                                                                                        {{$data_kom7->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom7->waktu))}}</td>
                                                                                <td>{{$data_kom7->tempat}}</td>
                                                                                <td>{{$data_kom7->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom7->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom7->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom7->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom7->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom7->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom7->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo8"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom8->id}}">
                                                                                        {{$data_kom8->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom8->waktu))}}</td>
                                                                                <td>{{$data_kom8->tempat}}</td>
                                                                                <td>{{$data_kom8->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom8->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom8->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom8->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom8->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom8->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom8->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo9"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                            @foreach ($kom9_leaderships as $data_kom9)
                                                                            <tr>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom9->id}}">
                                                                                        {{$data_kom9->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom9->waktu))}}</td>
                                                                                <td>{{$data_kom9->tempat}}</td>
                                                                                <td>{{$data_kom9->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom9->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom9->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom9->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom9->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom9->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_leadership/' . $data_kom9->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <h5>Data Kegiatan Karakter Islami</h5>
                                        </div>
                                        <br>
                                        <div class="panel-body">
                                            <div style="overflow: scroll">
                                                <table class="table table-condensed" style="border-collapse:collapse;">

                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th><strong>Kode</strong></th>
                                                            <th><strong>Komponen</strong></th>
                                                            <th><strong>Jumlah</strong></th>
                                                            <th><strong>Presentase</strong></th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr data-toggle="collapse" data-target="#demo1"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom1->id}}">
                                                                                        {{$data_kom1->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom1->waktu))}}</td>
                                                                                <td>{{$data_kom1->tempat}}</td>
                                                                                <td>{{$data_kom1->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom1->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom1->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom1->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom1->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom1->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom1->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo2"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom2->id}}">
                                                                                        {{$data_kom2->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom2->waktu))}}</td>
                                                                                <td>{{$data_kom2->tempat}}</td>
                                                                                <td>{{$data_kom2->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom2->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom2->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom2->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom2->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom2->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom2->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo3"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom3->id}}">
                                                                                        {{$data_kom3->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom3->waktu))}}</td>
                                                                                <td>{{$data_kom3->tempat}}</td>
                                                                                <td>{{$data_kom3->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom3->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom3->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom3->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom3->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom3->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom3->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo4"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom4->id}}">
                                                                                        {{$data_kom4->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom4->waktu))}}</td>
                                                                                <td>{{$data_kom4->tempat}}</td>
                                                                                <td>{{$data_kom4->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom4->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom4->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom4->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom4->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom4->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom4->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo5"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom5->id}}">
                                                                                        {{$data_kom5->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom5->waktu))}}</td>
                                                                                <td>{{$data_kom5->tempat}}</td>
                                                                                <td>{{$data_kom5->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom5->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom5->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom5->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom5->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom5->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom5->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo6"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom6->id}}">
                                                                                        {{$data_kom6->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom6->waktu))}}</td>
                                                                                <td>{{$data_kom6->tempat}}</td>
                                                                                <td>{{$data_kom6->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom6->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom6->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom6->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom6->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom6->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom6->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo7"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom7->id}}">
                                                                                        {{$data_kom7->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom7->waktu))}}</td>
                                                                                <td>{{$data_kom7->tempat}}</td>
                                                                                <td>{{$data_kom7->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom7->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom7->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom7->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom7->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom7->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom7->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo8"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom8->id}}">
                                                                                        {{$data_kom8->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom8->waktu))}}</td>
                                                                                <td>{{$data_kom8->tempat}}</td>
                                                                                <td>{{$data_kom8->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom8->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom8->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom8->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom8->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom8->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom8->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo9"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                            @foreach ($kom9_karakters as $data_kom9)
                                                                            <tr>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom9->id}}">
                                                                                        {{$data_kom9->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom9->waktu))}}</td>
                                                                                <td>{{$data_kom9->tempat}}</td>
                                                                                <td>{{$data_kom9->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom9->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom9->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom9->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom9->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom9->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_karakter/' . $data_kom9->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">

                                        <div class="panel-heading">
                                            <h5>Data Kegiatan Kreativitas & Kewirausahaan</h5>
                                        </div>
                                        <br>
                                        <div class="panel-body">
                                            <div style="overflow: scroll">
                                                <table class="table table-condensed" style="border-collapse:collapse;">

                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th><strong>Kode</strong></th>
                                                            <th><strong>Komponen</strong></th>
                                                            <th><strong>Jumlah</strong></th>
                                                            <th><strong>Presentase</strong></th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr data-toggle="collapse" data-target="#demo1"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom1->id}}">
                                                                                        {{$data_kom1->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom1->waktu))}}</td>
                                                                                <td>{{$data_kom1->tempat}}</td>
                                                                                <td>{{$data_kom1->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom1->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom1->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom1->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom1->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom1->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom1->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo2"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom2->id}}">
                                                                                        {{$data_kom2->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom2->waktu))}}</td>
                                                                                <td>{{$data_kom2->tempat}}</td>
                                                                                <td>{{$data_kom2->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom2->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom2->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom2->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom2->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom2->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom2->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr data-toggle="collapse" data-target="#demo3"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
                                                            <td>4003</td>
                                                            <td>Membaca buku, majalah, internet dll terkait kewirausahaan
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom3->id}}">
                                                                                        {{$data_kom3->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom3->waktu))}}</td>
                                                                                <td>{{$data_kom3->tempat}}</td>
                                                                                <td>{{$data_kom3->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom3->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom3->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom3->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom3->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom3->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom3->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo4"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom4->id}}">
                                                                                        {{$data_kom4->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom4->waktu))}}</td>
                                                                                <td>{{$data_kom4->tempat}}</td>
                                                                                <td>{{$data_kom4->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom4->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom4->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom4->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom4->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom4->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom4->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo5"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom5->id}}">
                                                                                        {{$data_kom5->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom5->waktu))}}</td>
                                                                                <td>{{$data_kom5->tempat}}</td>
                                                                                <td>{{$data_kom5->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom5->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom5->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom5->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom5->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom5->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom5->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo6"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom6->id}}">
                                                                                        {{$data_kom6->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom6->waktu))}}</td>
                                                                                <td>{{$data_kom6->tempat}}</td>
                                                                                <td>{{$data_kom6->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom6->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom6->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom6->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom6->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom6->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom6->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo7"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom7->id}}">
                                                                                        {{$data_kom7->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom7->waktu))}}</td>
                                                                                <td>{{$data_kom7->tempat}}</td>
                                                                                <td>{{$data_kom7->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom7->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom7->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom7->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom7->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom7->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom7->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr data-toggle="collapse" data-target="#demo8"
                                                            class="accordion-toggle">
                                                            <td><button class="btn btn-default btn-xs"><span
                                                                        class="mdi mdi-format-list-bulleted-square"></span></button>
                                                            </td>
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
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-link"
                                                                                        data-toggle="modal"
                                                                                        data-target="#detailModal{{$data_kom8->id}}">
                                                                                        {{$data_kom8->kegiatan}}
                                                                                    </button>
                                                                                </td>
                                                                                <td>{{date('d-m-Y',
                                                                                    strtotime($data_kom8->waktu))}}</td>
                                                                                <td>{{$data_kom8->tempat}}</td>
                                                                                <td>{{$data_kom8->keterangan}}</td>
                                                                            </tr>
                                                                            <!-- Modal -->
                                                                            <div class="modal fade"
                                                                                id="detailModal{{$data_kom8->id}}"
                                                                                tabindex="-1" role="dialog"
                                                                                aria-labelledby="detailModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title"
                                                                                                id="detailModalLabel">
                                                                                                {{$data_kom8->kegiatan}}
                                                                                                Detail</h5>
                                                                                            <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Waktu: {{date('d-m-Y',
                                                                                                strtotime($data_kom8->waktu))}}
                                                                                            </p>
                                                                                            <p>Tempat:
                                                                                                {{$data_kom8->tempat}}</p>
                                                                                            <p>Keterangan:
                                                                                                {{$data_kom8->keterangan}}
                                                                                            </p>
                                                                                            <br>
                                                                                            <div
                                                                                                class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg">
                                                                                                <div
                                                                                                    class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                                                                                                    <h4>Foto Kegiatan</h4>
                                                                                                    <br>
                                                                                                    <address>
                                                                                                        <img src="{{ asset('storage/data_file_kreatif/' . $data_kom8->file) }}"
                                                                                                            class="img-thumbnail"
                                                                                                            style="height:auto; width:100%; vertical-align:middle; max-width:250px;"
                                                                                                            alt="">
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary"
                                                                                                data-dismiss="modal">Tutup</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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
