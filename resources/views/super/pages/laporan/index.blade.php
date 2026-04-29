@extends('super.layouts.master')

@section('title') Laporan SIMONAS @endsection

@section('css')
    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title') 
         Laporan
         
        @endslot
         @slot('title_li')  <button onclick="window.print()" class="btn btn-secondary mb-4">
            <i class="fa fa-print"></i> Print
        </button>  @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="badge badge-primary text-white text-center font-size-14">Jumlah Aspek Kegiatan</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Akademik</h5>
                                    </div>
                                    <h3>{{$dt_akademiks}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Leadership</h5>
                                    </div>
                                    <h3>{{$dt_leaderships}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Karakter Islami</h5>
                                    </div>
                                    <h3>{{$dt_karakters}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h5 class="card-title">Kreatifitas</h5>
                                    </div>
                                    <h3>{{$dt_kreatifs}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <p class="badge badge-primary text-white text-center font-size-14">Kegiatan Terbaik Warga Asrama</p>
                    <div class="row">
                        @foreach ($dt_kegiatan as $item)
                            
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ $item->avatar && file_exists(public_path('data_photo/' . $item->avatar)) 
                                                    ? url('/data_photo/' . $item->avatar) 
                                                    : url('/data_photo/default_photo.jpg') }}" 
                                             alt="Avatar" 
                                             class="rounded-circle avatar-lg">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <p class="badge badge-info mt-3">{{$item->name}}</p>
                                        <p>{{$item->asrama}}</p>
                                        <h4>{{$item->total_kegiatan}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <br>
                    <p class="badge badge-primary text-white text-center font-size-14">IPK Terbaik Warga Asrama</p>
                    <div class="row">
                        @foreach ($dt_ipk as $item)
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ $item->avatar && file_exists(public_path('data_photo/' . $item->avatar)) 
                                                    ? url('/data_photo/' . $item->avatar) 
                                                    : url('/data_photo/default_photo.jpg') }}" 
                                             alt="Avatar" 
                                             class="rounded-circle avatar-lg">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <p class="badge badge-info mt-3">{{$item->name}}</p>
                                        <p>{{$item->asrama}}</p>
                                        <p>{{$item->universitas}}</p>
                                        <h4>{{ number_format($item->rata_rata_ip, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js')}}"></script>
    
    <!-- Calendar init -->
    <script src="{{ URL::asset('js/pages/dashboard.init.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{URL::asset('/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- apexcharts init -->
    <script src="{{URL::asset('/js/pages/apexcharts.init.js')}}"></script>

    <!-- Select2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

@endsection