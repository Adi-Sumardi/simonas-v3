@extends('mahasiswa.layouts.master-layouts')

@section('title')
    Filter Jumlah Kegiatan
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
@endsection

@section('content')
    @component('mahasiswa.common-components.breadcrumb')
        @slot('title')
            Jumlah Kegiatan
        @endslot
        @slot('li_1')
            Pages
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3"><strong>FILTER JUMLAH KEGIATAN</strong></div>
                    <div class="row justify-content-center pt-3 pb-3">
                        <div class="col-md-3 col-6">
                            <label>Dari Tanggal</label>
                            <input readonly type="text" class="form-control" value="{{ $startDate}}">
                        </div>
                        <div class="col-md-3 col-6">
                            <label>Sampai Tanggal</label>
                            <input readonly type="text" class="form-control" value="{{ $endDate}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <p>Akademik</p>
                                    <p><strong>{{$activityCount}}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <p>Leadership</p>
                                    <p><strong>{{$leadershipCount}}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <p>Karakter Islami</p>
                                    <p><strong>{{$karakterCount}}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <p>Kreatifitas dan Kewirausahaan</p>
                                    <p><strong>{{$kreatifCount}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <h5>#Kegiatan Akademik</h5>
                    <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($akademiks as $item)
                                <tr>
                                    <td>{{ $item->kegiatan }}</td>
                                    <td>{{ $item->waktu }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br>
                    <h5>#Kegiatan Leadership</h5>
                    <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaderships as $item)
                                <tr>
                                    <td>{{ $item->kegiatan }}</td>
                                    <td>{{ $item->waktu }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br>
                    <h5>#Kegiatan Karakter Islami</h5>
                    <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karakters as $item)
                                <tr>
                                    <td>{{ $item->kegiatan }}</td>
                                    <td>{{ $item->waktu }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br>
                    <h5>#Kegiatan Kreatifitas dan Kewirausahaan</h5>
                    <table class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kreatifs as $item)
                                <tr>
                                    <td>{{ $item->kegiatan }}</td>
                                    <td>{{ $item->waktu }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

@endsection
