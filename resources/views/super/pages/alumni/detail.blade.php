@extends('super.layouts.master')

@section('title') Profile @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('super.common-components.breadcrumb')
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
                                                <img src="{{ asset('images/' .$alumni->foto)}}" alt="Foto" style="border-radius: 50%; object-fit: cover;"  width="150" height="150">
                                                <div class="online-circle"><br><br><br>
                                            <i class="fas fa-circle text-success"></i></div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="mt-3">
                                                    <h6 >{{$alumni->nama}}</h6>
                                                    <p class="font-size-12 text-muted mb-1">{{$Asrama->nama_asrama}}</p>

                                                    <div style="display: inline-block; padding: 10px; background-color: skyblue; border-radius: 15%; height: 35px; width:120px;">
                                                        <h6 style="display: flex; align-items: center; justify-content: center; margin: 0;">Alumni</h6>
                                                    </div>

                                                                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3" style="border-bottom: 2px solid #f9f6f6; padding-bottom: 10px;">BIODATA</h5>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Tempat dan Tanggal Lahir</p>
                                        <h6 class="">{{$alumni->provinsi_asal}},{{ \Carbon\Carbon::parse($alumni->tanggal_lahir)->format('d M Y') }}</h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Kota Asal</p>
                                        <h6 class="">{{$alumni->id_province}}</h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Alamat Domisili</p>
                                        <h6 class="">{{$alumni->alamat_domisili}}</h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Nomer Telpon / WA</p>
                                        <h6 class="">{{$alumni->no_whatsapp}}</h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Email</p>
                                        <h6 class="">{{$alumni->email}}</h6>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-1 col-xl-9">
                            <div class="row mx-n2">
                                <div class="col-md-4 px-2">
                                    <div class="card h-100 border">
                                        <div class="card-body">
                                            <fieldset  style="height: 560px;">
                                                <h4 class="card-title mb-4" style="border-bottom: 2px solid #f9f6f6; padding-bottom: 10px;"><strong>DATA KEASRAMAAN</strong></h4>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Asal Asrama</p>
                                                    <h6>{{$Asrama->nama_asrama}}</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mt-3">
                                                            <p class="font-size-12 text-muted mb-1">Tahun Masuk</p>
                                                            <h6>{{$alumni->tahun_masuk_asrama}}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-3">
                                                            <p class="font-size-12 text-muted mb-1">Tahun Keluar</p>
                                                            <h6>{{$alumni->tahun_keluar_asrama}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Jabatan Terakhir</p>
                                                    <h6 class="">{{$Asrama->ketua}}</h6>
                                                </div>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Nama Direktur</p>
                                                    <h6 class="">{{$Asrama->direktur}}</h6>
                                                </div>
                                                <div class="mt-3 mb-auto">
                                                    <p class="font-size-12 text-muted mb-1">Teman Satu Angkatan</p>
                                                    @foreach(explode('@', $alumni->teman_angkatan) as $teman)
    <h6 class="">{{$teman}}</h6>
@endforeach

                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 px-1">
                                    <div class="card">
                                        <div class="card-body">
                                            <fieldset style="height: 580px; overflow-y: auto;">
                                                <h4 class="card-title mb-2" ><strong>PENDIDIKAN</strong></h4>
                                                <div class="form-group row">
                                                      <div class="container">
                                                        <div class="row">
                                                          <div class="col-md-5">
                                                            <div class="mt-3 mb-auto">
                                                                <p class="font-size-12 text-muted mb-1">Jenjang Pendidikan Terakhir</p>
                                                                @php
                                                                $sortedPendidikan = $AlumniPendidikan->sortByDesc(function ($pendidikan) {
                                                                    switch ($pendidikan->gelar) {
                                                                        case 'S3':
                                                                            return 3;
                                                                        case 'S2':
                                                                            return 2;
                                                                        case 'S1':
                                                                            return 1;
                                                                        default:
                                                                            return 0;
                                                                    }
                                                                });

                                                            @endphp

                                                            @if($sortedPendidikan->isNotEmpty())
                                                                @php
                                                                    $recentPendidikan = $sortedPendidikan->first();
                                                                @endphp

                                                                <div class="mt-1"><br>
                                                                    <h6 class="">{{$recentPendidikan->gelar}}</h6>
                                                                </div><br><br>
                                                            @endif
                                                                                                  </div>
                                                     </div>
                                                          <div class="col-md-1" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                            @foreach($AlumniPendidikan as $pendidikan)
                                                            <div class="mt-1 "><br>
                                                                <h6 class="">{{$pendidikan->gelar}}</h6>
                                                            </div><br><br>
                                                            @endforeach
                                                          </div>
                                                          <div class="col-md-5">
                                                            <div class="mt-3 ">
                                                                @foreach($AlumniPendidikan as $AlumniPendidikan)
                                                                <p class="font-size-12 text-muted mb-1">Jurusan</p>
                                                                <h6 class="">{{$AlumniPendidikan->fakultas_jurusan}}</h6>
                                                                <p class="font-size-12 text-muted mb-1">Universitas</p>
                                                                <h6 class="">{{$AlumniPendidikan->nama_kampus}}</h6>
                                                                @endforeach
                                                            </div>
                                                          </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                    <h4 class="card-title mb-2"><strong>PEKERJAAN</strong></h4>
                                                    <div class="form-group row">
                                                        <div class="container">
                                                            <div class="row">
                                                              <div class="col-md-2">
                                                                <div class="mt-3" >
                                                                    <p class="font-size-12 text-muted mb-1" >Jabatan</p>
                                                                    @foreach($AlumniPekerjaan as $namjab)
                                                                    <h6 class="mb-2">{{$namjab->tempat_pekerjaan}}</h6>
                                                                    @endforeach
                                                                </div>
                                                              </div>
                                                              <div class="col-md-6" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                                <div class="mt-3">
                                                                    <p class="font-size-12 text-muted mb-1">Perusahaan</p>
                                                                    @foreach($AlumniPekerjaan as $perusahaan)
                                                                    <h6 class="mb-2">{{$perusahaan->bidang_pekerjaan}}</h6>
                                                                    @endforeach
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <div class="mt-3 mb-auto">
                                                                    <p class="font-size-12 text-muted mb-1">Tahun</p>
                                                                    @foreach($AlumniPekerjaan as $tahun)
                                                                    <h6 class="mb-2">{{$tahun->tahun_kerjaan}}</h6>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
<br>
                                                    <h4 class="card-title mb-2"><strong>ORGANISASI</strong></h4>
                                                    <div class="form-group row">
                                                        <div class="container">
                                                            <div class="row">
                                                              <div class="col-md-4">
                                                                <div class="mt-3 mb-auto" >
                                                                    <p class="font-size-12 text-muted mb-1" >Jabatan</p>
                                                                    @foreach($AlumniOrganisasi as $namjab)
                                                                    <h6 class="">{{$namjab->organisasi_jabatan}}</h6>
                                                                    @endforeach
                                                                </div>
                                                              </div>
                                                              <div class="col-md-5" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                                <div class="mt-3">
                                                                    <p class="font-size-12 text-muted mb-1">Organisasi</p>
                                                                    @foreach($AlumniOrganisasi as $namjab)
                                                                    <h6 class="">{{$namjab->nama}}</h6>
                                                                @endforeach
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <div class="mt-3 mb-auto">
                                                                    <p class="font-size-12 text-muted mb-1">Tahun</p>
                                                                    @foreach($AlumniOrganisasi as $namjab)
                                                                    <h6 class="">{{$namjab->tahun_organisasi}}</h6>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    </fieldset>
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


    @endsection
