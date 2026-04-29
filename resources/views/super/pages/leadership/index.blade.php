@extends('super.layouts.master')

@section('title')
    Daftar Kegiatan Leadership
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('super.common-components.breadcrumb')
        @slot('title')
            Daftar Kegiatan Leadership
        @endslot
        @slot('title_li')
            Daftar Kegiatan Leadership
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('super-kegiatan-leadership-create')}}" type="button"
                            class="btn btn-outline-primary waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                        </a>
                    </div>
                    <br>

                    <h4 class="card-title">Daftar Kegiatan Leadership</h4>
                    <br>
                    <div style="overflow: scroll">
                        {{--  <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"  --}}
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Warga</th>
                                    <th>Komponen</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaderships as $index => $leadership)
                                    <tr>
                                        <td>{{ $index + $leaderships->firstItem() }}</td>
                                        <td>{{ $leadership->nama_warga }}</td>
                                        <td>{{ $leadership->komponen }}</td>
                                        <td>{{ date('d-m-Y', strtotime($leadership->waktu)) }}</td>
                                        <td>{{ $leadership->tempat }}</td>
                                        <td>
                                            <a href="/super-kegiatan-leadership-detail/{{ $leadership->id }}"
                                                class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail"> View</a>
                                            {{--  <form action="/super-kegiatan-akademik-delete/{{ $akademik->id }}"
                                                method="POST">
                                                <a href="/super-kegiatan-akademik-edit/{{ $akademik->id }}"
                                                    class="btn btn-secondary btn-action" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>  --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        Jumlah Data: {{$leaderships->total()}}
                        <br/>
                        <br/>
                        {{$leaderships->links()}}
                    </div>
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
