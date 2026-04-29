@extends('mahasiswa.layouts.master-layouts')

@section('title')
    Update Kartu Hasil Studi
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Plugins css -->
    <link href="{{URL::asset('/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('mahasiswa.common-components.breadcrumb')
        @slot('title')
        Update Kartu Hasil Studi
        @endslot
        @slot('title_li')
        Update Kartu Hasil Studi
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="/mahasiswa-ipk-khs-update/{{$ipk->id}}" method="POST" enctype="multipart/form-data" class="dropzone">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="form-group">
                                <div>
                                    <div class="fallback">
                                        <input name="file" type="file">
                                    </div>
                                    <div class="dz-message needsclick">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted mdi mdi-upload-network-outline"></i>
                                        </div>
            
                                        <h4>Drop files here or click to upload.</h4>
                                    </div>
                                </div>
                
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save Files</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
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

    <!-- Plugins js -->
    <script src="{{URL::asset('/libs/dropzone/dropzone.min.js')}}"></script>
@endsection
