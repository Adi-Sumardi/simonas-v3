@extends('super.layouts.master')

@section('title') Profile @endsection

@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

@component('super.common-components.breadcrumb')
@slot('title') Profile @endslot
@slot('li_1') Pages @endslot
@endcomponent


<!-- start row -->
<div class="row">
    <div class="col-md-12 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="profile-widgets py-3">

                    <div class="text-center">
                        <div class="">
                            <img src="{{ url('/data_photo/' . $user->avatar) }}" alt=""
                                class="avatar-lg mx-auto img-thumbnail rounded-circle">
                            <div class="online-circle"><i class="fas fa-circle text-success"></i></div>
                        </div>

                        <div class="mt-3 ">
                            <a href="#" class="text-dark font-weight-medium font-size-16">{{$user->name}}</a>
                            <p class="text-body mt-1 mb-1">{{$user->asrama}}</p>

                        </div>

                        <div class="mt-4">

                            <ui class="list-inline social-source-list">
                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle">
                                            <i class="mdi mdi-facebook"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-info">
                                            <i class="mdi mdi-twitter"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-danger">
                                            <i class="mdi mdi-google-plus"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-pink">
                                            <i class="mdi mdi-instagram"></i>
                                        </span>
                                    </div>
                                </li>
                            </ui>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Personal Information</h5>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Email</p>
                    <h6 class="">{{$user->email}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tanggal Lahir</p>
                    <h6 class="">{{$user->tgl_lahir}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Nomor Telepon</p>
                    <h6 class="">{{$user->no_telp}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Alamat Asal</p>
                    <h6 class="">{{$user->alamat}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kecamatan</p>
                    <h6 class="">{{$user->kecamatan }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kota/Kabupaten</p>
                    <h6 class="">{{$user->kota }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Provinsi</p>
                    <h6 class="">{{$user->provinsi }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kampus</p>
                    <h6 class="">{{$user->universitas}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Fakultas</p>
                    <h6 class="">{{$user->fakultas}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Program Studi</p>
                    <h6 class="">{{$user->prodi}}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Organisasi</p>
                    <h6 class="">{{$user->organisasi}}</h6>
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
                                @if ($data_ipks !== null)
                                <h4 class="mb-0 badge badge-soft-success font-size-14">{{number_format($data_ipks, 2)}}
                                </h4>
                                @else
                                <h4 class="mb-0 badge badge-soft-success font-size-14">Data tidak tersedia</h4>
                                @endif
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        {{-- <i class="mdi mdi-arrow-up text-success ml-1"></i> --}}
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
                                <p class="mb-2">Status Warga</p>
                                <label class="mb-0 badge badge-soft-info font-size-14">{{$user->status_warga}}</label>
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        {{-- <i class="mdi mdi-arrow-up text-success ml-1"></i> --}}
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
                                <label class="mb-0 badge badge-soft-primary font-size-14">{{$user->no_induk}}</label>
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        {{-- <i class="mdi mdi-arrow-up text-success ml-1"></i> --}}
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

                        <div class="col-xl-8">
                            <div class="form-group">
                                <label for="">Filter by date:</label>

                                <form action="{{ route('super-warga-asrama-detail', ['id' => $id]) }}" method="get">
                                    <div class="input-group">
                                        <select class="form-select" name="date_filter">
                                            <option value="">All Dates</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="this_week">This Week</option>
                                            <option value="last_week">Last Week</option>
                                            <option value="this_month">This Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="this_year">This Year</option>
                                            <option value="last_year">Last Year</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>

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
                                                    @foreach ($komponen as $akademik)
                                                    @if ($akademik->aspek == 'Akademik')
                                                    <tr data-toggle="collapse" data-target="#demo{{ $akademik->id }}"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> {{ $akademik->kode }}</td>
                                                        <td>{{ $akademik->nama_komponen }}</td>
                                                        {{-- <td>{{$kom1_akademiks_count}}</td> --}}

                                                        <td>{{ $akademiks->where('komponen',
                                                            $akademik->nama_komponen)->count() }}</td>
                                                        <td>
                                                            @if ($akademiks->count() > 0)
                                                            {{ number_format(($akademiks->where('komponen',
                                                            $akademik->nama_komponen)->count() / $akademiks->count()) *
                                                            100, 2) }}%
                                                            @else
                                                            0%
                                                            @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="demo{{ $akademik->id }}">
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

                                                                        @foreach ($akademiks->where('komponen',
                                                                        $akademik->nama_komponen) as $akademika)
                                                                        @if(is_object($akademika))
                                                                        <tr>
                                                                            <td>{{ $akademika->kegiatan }}</td>
                                                                            <td>{{ date('d-m-Y',
                                                                                strtotime($akademika->waktu)) }}</td>
                                                                            <td>{{ $akademika->tempat }}</td>
                                                                            <td>{{ $akademika->keterangan }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        @endforeach
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        @endforeach
                                                    </tr>


                                                    {{-- <tr data-toggle="collapse" data-target="#demo3"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>
                                                        <td>1003</td>
                                                        <td>Mengikuti forum akademik</td>
                                                        <td>{{$kom3_akademiks_count}}</td>
                                                        <td>0%</td>

                                                    </tr> --}}
                                                    {{-- <tr>
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
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom3->waktu))}}</td>
                                                                            <td>{{$data_kom3->tempat}}</td>
                                                                            <td>{{$data_kom3->keterangan}}</td>
                                                                        </tr>
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
                                                                            <td>{{$data_kom4->kegiatan}}</td>
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom4->waktu))}}</td>
                                                                            <td>{{$data_kom4->tempat}}</td>
                                                                            <td>{{$data_kom4->keterangan}}</td>
                                                                        </tr>
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
                                                                            <td>{{$data_kom5->kegiatan}}</td>
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom5->waktu))}}</td>
                                                                            <td>{{$data_kom5->tempat}}</td>
                                                                            <td>{{$data_kom5->keterangan}}</td>
                                                                        </tr>
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
                                                                            <td>{{$data_kom6->kegiatan}}</td>
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom6->waktu))}}</td>
                                                                            <td>{{$data_kom6->tempat}}</td>
                                                                            <td>{{$data_kom6->keterangan}}</td>
                                                                        </tr>
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
                                                                            <td>{{$data_kom7->kegiatan}}</td>
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom7->waktu))}}</td>
                                                                            <td>{{$data_kom7->tempat}}</td>
                                                                            <td>{{$data_kom7->keterangan}}</td>
                                                                        </tr>
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
                                                                            <td>{{$data_kom8->kegiatan}}</td>
                                                                            <td>{{date('d-m-Y',
                                                                                strtotime($data_kom8->waktu))}}</td>
                                                                            <td>{{$data_kom8->tempat}}</td>
                                                                            <td>{{$data_kom8->keterangan}}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr> --}}
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
                                                    @foreach ($komponen as $Leader)
                                                    @if ($Leader->aspek == 'Leadership')
                                                    <tr data-toggle="collapse" data-target="#dewi{{ $Leader->id }}"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> {{ $Leader->kode }}</td>
                                                        <td>{{ $Leader->nama_komponen }}</td>
                                                        {{-- <td>{{$kom1_akademiks_count}}</td> --}}

                                                        <td>{{ $leaderships->where('komponen',
                                                            $Leader->nama_komponen)->count() }}</td>
                                                        <td>
                                                            @if ($leaderships->count() > 0)
                                                            {{ number_format(($leaderships->where('komponen',
                                                            $Leader->nama_komponen)->count() / $leaderships->count()) *
                                                            100, 2) }}%
                                                            @else
                                                            0%
                                                            @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi{{ $Leader->id }}">
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

                                                                        @foreach ($leaderships->where('komponen',
                                                                        $Leader->nama_komponen) as $leaderships11)
                                                                        @if(is_object($leaderships11))
                                                                        <tr>
                                                                            <td>{{ $leaderships11->kegiatan }}</td>
                                                                            <td>{{ date('d-m-Y',
                                                                                strtotime($leaderships11->waktu)) }}
                                                                            </td>
                                                                            <td>{{ $leaderships11->tempat }}</td>
                                                                            <td>{{ $leaderships11->keterangan }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        @endforeach
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        @endforeach
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
                                                    @foreach ($komponen as $karak)
                                                    @if ($karak->aspek == 'Karakter Islami')
                                                    <tr data-toggle="collapse" data-target="#dewi{{ $karak->id }}"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> {{ $karak->kode }}</td>
                                                        <td>{{ $karak->nama_komponen }}</td>
                                                        {{-- <td>{{$kom1_akademiks_count}}</td> --}}

                                                        <td>{{ $karakters->where('komponen',
                                                            $karak->nama_komponen)->count() }}</td>
                                                        <td>
                                                            @if ($karakters->count() > 0)
                                                            {{ number_format(($karakters->where('komponen',
                                                            $karak->nama_komponen)->count() / $karakters->count()) *
                                                            100, 2) }}%
                                                            @else
                                                            0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi{{ $karak->id }}">
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

                                                                        @foreach ($karakters->where('komponen',
                                                                        $karak->nama_komponen) as $karakters1)
                                                                        @if(is_object($karakters1))
                                                                        <tr>
                                                                            <td>{{ $karakters1->kegiatan }}</td>
                                                                            <td>{{ date('d-m-Y',
                                                                                strtotime($karakters1->waktu)) }}</td>
                                                                            <td>{{ $karakters1->tempat }}</td>
                                                                            <td>{{ $karakters1->keterangan }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        @endforeach
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        @endforeach
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
                                    <div class="panel-heading">
                                        <h5>Data Kegiatan Kreativitas & Kewirausahaan
                                        </h5>
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
                                                    @foreach ($komponen as $krea)
                                                    @if ($krea->aspek == 'Kreativitas & Kewirausahaan')
                                                    <tr data-toggle="collapse" data-target="#dewi{{ $krea->id }}"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> {{ $krea->kode }}</td>
                                                        <td>{{ $krea->nama_komponen }}</td>
                                                        {{-- <td>{{$kom1_akademiks_count}}</td> --}}

                                                        <td>{{ $kreatifs->where('komponen',
                                                            $krea->nama_komponen)->count() }}</td>
                                                        <td>
                                                            @if ($kreatifs->count() > 0)
                                                            {{ number_format(($kreatifs->where('komponen',
                                                            $krea->nama_komponen)->count() / $kreatifs->count()) * 100,
                                                            2) }}%
                                                            @else
                                                            0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi{{ $krea->id }}">
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

                                                                        @foreach ($kreatifs->where('komponen',
                                                                        $krea->nama_komponen) as $kreatifs1)
                                                                        @if(is_object($kreatifs1))
                                                                        <tr>
                                                                            <td>{{ $kreatifs1->kegiatan }}</td>
                                                                            <td>{{ date('d-m-Y',
                                                                                strtotime($kreatifs1->waktu)) }}</td>
                                                                            <td>{{ $kreatifs1->tempat }}</td>
                                                                            <td>{{ $kreatifs1->keterangan }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        @endforeach
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        @endforeach
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
                                        <a href="/super-ipk-detail/{{$item->id}}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="mdi mdi-eye-circle-outline"></i> View</a>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="d-print-none">
    <div class="float-right">
        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i>
            Print</a>
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
<script src="{{ URL::asset('/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{ URL::asset('/js/pages/profile.init.js')}}"></script>

<!-- apexcharts init -->
<script src="{{URL::asset('/js/pages/apexcharts.init.js')}}"></script>

<script>
    var akademiksCount = {{ $akademiks->count() }};
            var leadershipsCount = {{ $leaderships->count() }};
            var karaktersCount = {{ $karakters->count() }};
            var kreatifsCount = {{ $kreatifs->count() }};
                var options = {
                    chart: {
                        height: 320,
                        type: 'pie',
                    },
                series: [akademiksCount, leadershipsCount, karaktersCount, kreatifsCount],
                labels: ["Akademik", "Leadership", "Karakter Islami", "Kreativitas & Kewirausahaan"],
                colors: ["#45cb85", "#3b5de7","#ff715b", "#eeb902"],
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
    function generateRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                            }
           const akademiklabel = {!! json_encode($komponen->where('aspek', 'Akademik')->pluck('nama_komponen')) !!};
           const seriesnama = @json($akademiks->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray());
            const countsMap = {};
            seriesnama.forEach(nama => {
                countsMap[nama] = (countsMap[nama] || 0) + 1;
            });
            const seriesData = akademiklabel.map(label => countsMap[label] || 0);
            const colorsak = akademiklabel.map(() => generateRandomColor())
            var options = {
                chart: {
                    height: 320,
                    type: 'donut',
                },
                series:seriesData,
                labels:akademiklabel,
                color:colorsak,
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0,
                },
                plotOptions: {
                pie: {
                donut: {
                    size: '50%', // ukuran donut
                },
                customScale: 1,
                offsetX: 0,
                offsetY: 0,
                dataLabels: {
                    formatter: function (val, opts) {
                        return "Akademik";
                    },
                    dropShadow: {
                        enabled: false
                    }
                }
            }
        },
    };
    var chart = new ApexCharts(document.querySelector("#akademik_chart"), options);
    chart.render();
</script>
<script>
    const leadership = {!! json_encode($komponen->where('aspek', 'Leadership')->pluck('nama_komponen')) !!};
            const seriesnamalead = @json($leaderships->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray());
            const countsMapled = {};
            seriesnamalead.forEach(nama => {
                countsMapled[nama] = (countsMapled[nama] || 0) + 1;
            });
            const seriesDatalead = leadership.map(label => countsMapled[label] || 0);
            const colorlead= leadership.map(() => generateRandomColor());
            var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDatalead,
            labels:leadership,
            color:colorlead,
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
    const leaderkreatif = {!! json_encode($komponen->where('aspek', 'Kreativitas & Kewirausahaan')->pluck('nama_komponen')) !!};
            const serieskreatif= @json($kreatifs->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray());
            const countsMapkreatif = {};
            serieskreatif.forEach(nama => {
                countsMapkreatif[nama] = (countsMapkreatif[nama] || 0) + 1;
            });
            const seriesDataKreatif = leaderkreatif.map(label => countsMapkreatif[label] || 0);
            const colorkreatif= leaderkreatif.map(() => generateRandomColor());
            console.log("seriesnya karakter",seriesDataKreatif)
            console.log("labelnya",leaderkreatif)
        var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDataKreatif,
            labels:leaderkreatif,
            colors:colorkreatif,
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

<script>
    const leaderkarakters = {!! json_encode($komponen->where('aspek', 'Karakter Islami')->pluck('nama_komponen')) !!};
            const serieskarakters= @json($karakters->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray());
            const countsMapkarakters = {};
            serieskarakters.forEach(nama => {
                countsMapkarakters[nama] = (countsMapkarakters[nama] || 0) + 1;
            });
            const seriesDatakarakters = leaderkarakters.map(label => countsMapkarakters[label] || 0);
            const colorkarakters= leaderkarakters.map(() => generateRandomColor());
        var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDatakarakters,
            labels:leaderkarakters,
            colors:colorkarakters,
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
@endsection