@extends('mahasiswa.layouts.master-layouts')

@section('title') Profile @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
   
    @component('mahasiswa.common-components.breadcrumb')
         @slot('title') Profile  @endslot
         @slot('li_1') Pages  @endslot
     @endcomponent


                    <!-- start row -->
                    <div class="row">
                        <div class="col-md-12 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-widgets py-3">

                                        <div class="text-center">
                                            <div class="">
                                                <img src="{{ url('/data_photo/' . $user->avatar) }}" alt="" class="avatar-lg mx-auto img-thumbnail rounded-circle">
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
                                                    <h4 class="mb-0 badge badge-soft-success font-size-14">{{number_format($data_ipks, 2)}}</h4>
                                                    @else
                                                    <h4 class="mb-0 badge badge-soft-success font-size-14">Data tidak tersedia</h4>
                                                    @endif
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
                                                    <p class="mb-2">Status Warga</p>
                                                    <label class="mb-0 badge badge-soft-info font-size-14">{{$user->status_warga}}</label>
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
                                                    <label class="mb-0 badge badge-soft-primary font-size-14">{{$user->no_induk}}</label>
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
                                                            <a href="/mahasiswa-ipk-detail/{{$item->id}}" class="btn btn-outline-primary btn-sm">
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
                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i> Print</a>
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
        // pie chart total
        var options = {
            chart: {
                height: 320,
                type: 'pie',
            }, 
            series: [{{$akademiks}}, {{$leaderships}}, {{$karakters}}, {{$kreatifs}}],
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
    @endsection