@extends($layouts)

@section('title')
    List Alumni Asrama
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('super.common-components.breadcrumb')
        @slot('title')
        List Alumni Asrama
        @endslot
        @slot('title_li')
        List Alumni Asrama
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Import Data Asset</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('super-alumni-asrama-import')}}" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="file" name="file" required="required">
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(auth()->user()->role =="super")
                      <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('super-alumni-asrama-create')}}" type="button"
                            class="btn btn-outline-primary waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                        </a>
                        <a href="{{ route('super-alumni-asrama-export')}}" type="button"
                            class="btn btn-outline-success  waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Export
                        </a>
                        <a href="{{ route('super-alumni-asrama-create')}}" type="button"
                            class="btn btn-outline-danger waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Import
                        </a>
                        <!-- <a href="{{ route('super-alumni-asrama-export')}}" type="button"
                            class="btn btn-outline-secondary waves-effect waves-light">
                            <i class="mdi mdi-file-export-outline font-size-16 align-middle mr-2"></i> Export Data
                        </a> -->
                        <!-- <button type="button"
                            class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center">
                            <i class="mdi mdi-file-import-outline font-size-16 align-middle mr-2" data-toggle="modal" data-target=".bs-example-modal-center"></i> Import Data
                        </button> -->
                        {{--  <a href="{{ route('super-alumni-asrama-import')}}" type="button"
                            class="btn btn-outline-success waves-effect waves-light">
                            <i class="mdi mdi-file-import-outline font-size-16 align-middle mr-2"></i> Import Data
                        </a>  --}}
                    </div>
                    <br>
                    @endif
                    <h4 class="card-title">List Alumni Asrama</h4>
                    <div style="overflow: scroll; overflow-x: auto;">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Asrama</th>
                                    <th>Tahun Masuk</th>
                                    <th>Tahun Keluar</th>
                                    <th>Jenjang Pendidikan</th>
                                    <th>Perguruan Tinggi</th>
                                    <th>Bidang Pekerjaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alumnis as $alumni)
                                    <tr>
                                        <td>{{$alumni->nama}}</td>
                                        <td> {{$alumni->asrama->nama_asrama}}<br>
                                        </td>
                                        <td>{{$alumni->tahun_masuk_asrama}}</td>
                                        <td>{{$alumni->tahun_keluar_asrama}}</td>
                                        <td>            @foreach($alumni->pendidikan as $pendidikan)
                                            {{$pendidikan->gelar}} <br>
                                        @endforeach</td>
                                        <td>@foreach($alumni->pendidikan as $pendidikan)
                                            {{$pendidikan->nama_kampus}} <br>
                                        @endforeach</td>
                                        <td>@foreach($alumni->pekerjaan as $pendidikan)
                                            {{$pendidikan->bidang_pekerjaan}} <br>
                                        @endforeach</td>
                                        <td>
                                            <a href="/super-alumni-asrama-detail/{{ $alumni->id }}"
                                                class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail"><i
                                                    class="far fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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