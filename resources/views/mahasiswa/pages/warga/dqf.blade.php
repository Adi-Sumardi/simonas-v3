@extends('mahasiswa.layouts.master-layouts')

@section('title')
    List Warga Asrama Putri
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('mahasiswa.common-components.breadcrumb')
        @slot('title')
        List Warga Asrama Putri
        @endslot
        @slot('title_li')
        List Warga Asrama Putri
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient" style="background: linear-gradient(90deg, #c0fff3, #0095ff);">
                <div class="card-body">
                    <div class="button-items d-flex justify-content-center">
                        <a href="/mahasiswa-warga-asgj" type="button" class="btn btn-primary waves-effect waves-light">ASGJ</a>
                        <a href="/mahasiswa-warga-asg" type="button" class="btn btn-primary waves-effect waves-light">ASG</a>
                        <a href="/mahasiswa-warga-aws" type="button" class="btn btn-primary waves-effect waves-light">AWS</a>
                        <a href="/mahasiswa-warga-aspuri" type="button" class="btn btn-primary waves-effect waves-light">ASPURI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($users as $index => $user)
                        <div class="col-lg-6">
                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-md-2">
                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="{{ url('/data_photo/' . $user->avatar) }}" alt="Card image" height="40" width="40">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <a href="/mahasiswa-warga-asrama-detail/{{ $user->id }}" class="card-title">{{$user->name}}</a>
                                            <br>
                                            <h6 class="card-text">{{$user->asrama}}</h6>
                                            <p class="card-text"><small class="text-muted">{{$user->status_warga}}</small></p>
                                            @if ($user->average_ip !== null)
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: {{ number_format($user->average_ip, 2) }}
                                                    </small>
                                                </p>
                                            @else
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: Data tidak tersedia
                                                    </small>
                                                </p>
                                            @endif
                                            {{--  <p class="card-text"><small class="text-muted">IPK: {{$user->average_ip}}</small></p>  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js') }}"></script>

    <!-- Calendar init -->
    <script src="{{ URL::asset('js/pages/dashboard.init.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('/js/pages/sweet-alerts.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js') }}"></script>
@endsection
