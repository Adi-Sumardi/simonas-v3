@extends('super.layouts.master')

@section('title')
    Komponen Create
@endsection

@section('css')

    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title')
        Komponen Create
        @endslot
        @slot('title_li')
        Komponen Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <form method="POST" action="/super-komponen-store" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode">Kode Komponen</label>
                                        <input type="number" class="form-control @error('kode') is-invalid @enderror"
                                            name="kode">
                                    </div>
                                    @error('kode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_komponen">Nama Komponen</label>
                                        <input type="text" class="form-control @error('nama_komponen') is-invalid @enderror"
                                            name="nama_komponen">
                                    </div>
                                    @error('nama_komponen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_asrama"
                                            class="control-label">Aspek:</label><span
                                            class="text-danger">
                                        <select name="aspek" class="form-control {{ $errors->has('aspek') ? ' is-invalid': '' }}">
                                            <option value="">Pilih Aspek</option>
                                            <option value="Akademik">Akademik</option>
                                            <option value="Leadership">Leadership</option>
                                            <option value="Karakter Islami">Karakter Islami</option>
                                            <option value="Kreativitas & Kewirausahaan">Kreativitas & Kewirausahaan</option>
                                        </select>
                                    </div>
                                    @error('aspek')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js')}}"></script>

@endsection