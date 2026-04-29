@extends('mahasiswa.layouts.master-layouts')

@section('title')
    Daftar Kegiatan Asrama
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    @component('mahasiswa.common-components.breadcrumb')
        @slot('title')
            Daftar Kegiatan Asrama
        @endslot
        @slot('title_li')
            Daftar Kegiatan Asrama
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('mahasiswa-kegiatan-asrama-create')}}" type="button"
                            class="btn btn-outline-primary waves-effect waves-light">
                            <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                        </a>
                    </div>
                    <br>

                    <h4 class="card-title">Daftar Kegiatan Asrama</h4>
                    <br>
                    <div style="overflow: scroll">
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Penyelenggara</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kegiatans as $index => $kegiatan)
                                    <tr>
                                        <td>{{ $index + $kegiatans->firstItem() }}</td>
                                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                                        <td>{{ $kegiatan->penyelenggara }}</td>
                                        <td>{{ date('d-m-Y', strtotime($kegiatan->waktu)) }}</td>
                                        <td>{{ $kegiatan->tempat }}</td>
                                        <td>
                                            <form action="/mahasiswa-kegiatan-asrama-delete/{{ $kegiatan->id }}"
                                                method="POST">
                                                <?php if ($userStatus == 'Pengurus Asrama'): ?>
                                                    <a href="/mahasiswa-attendance/{{{ $kegiatan->id }}}" class="btn btn-info btn-action" data-toggle="tooltip" data-placement="top" title="Attendance">
                                                        <i class="fas fa-user-clock"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="/mahasiswa-kegiatan-asrama-detail/{{ $kegiatan->id }}"
                                                    class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail"><i
                                                        class="far fa-eye"></i></a>
                                                {{--  <a href="/mahasiswa-kegiatan-asrama-edit/{{ $kegiatan->id }}"
                                                    class="btn btn-secondary btn-action" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fas fa-pencil-alt"></i></a>  --}}
                                                @csrf
                                                @method('DELETE')
                                                <?php if ($userStatus == 'Pengurus Asrama'): ?>
                                                <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fas fa-trash-alt"></i></button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        Jumlah Data: {{$kegiatans->total()}}
                        <br/>
                        <br/>
                        {{$kegiatans->links()}}
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
