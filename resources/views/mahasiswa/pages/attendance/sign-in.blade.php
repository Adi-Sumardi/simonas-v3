@extends('mahasiswa.layouts.master-layouts')

@section('title')
    Attendance Kegiatan
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    @component('mahasiswa.common-components.breadcrumb')
        @slot('title')
            Attendance
        @endslot
        @slot('title_li')
            Simonas
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">Nama Kegiatan</div>
                                    <p>{{$dataKegiatan->nama_kegiatan}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">Penyelenggara</div>
                                    <p>{{$dataKegiatan->penyelenggara}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="card-title">Tempat</div>
                                    <p>{{$dataKegiatan->tempat}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">Daftar Kehadiran Warga</div>
                                    <form action="{{ route('mahasiswa-attendance-store', $dataKegiatan->id) }}" method="POST">
                                        @csrf
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Kehadiran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataWargas as $warga)
                                                    <tr>
                                                        <td>{{ $warga->name }}</td>
                                                        <td>
                                                            <input type="checkbox" name="status_attendance[{{ $warga->id }}]" value="1">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label>Catatan Kegiatan:</label>
                                                <textarea class="form-control" name="noted" cols="30" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan Kehadiran</button>
                                    </form>
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
    <!-- plugin js -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js') }}"></script>

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
