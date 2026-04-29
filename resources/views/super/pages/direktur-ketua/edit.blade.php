@extends('super.layouts.master')

@section('title')
    Direktur & Ketua Asrama Edit
@endsection

@section('css')

    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title')
        Direktur & Ketua Asrama Edit
        @endslot
        @slot('title_li')
        Direktur & Ketua Asrama Edit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <form method="POST" action="/super-direktur-asrama-update/{{$asrama->id}}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_asrama"
                                            class="control-label">Nama Asrama:</label><span
                                            class="text-danger">
                                        <select name="nama_asrama" class="form-control {{ $errors->has('nama_asrama') ? ' is-invalid': '' }}">
                                            <option value="">Pilih Asrama</option>
                                            <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                            <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                            <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                            <option value="Asrama Putri">Asrama Putri</option>
                                        </select>
                                    </div>
                                    @error('nama_asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_jabatan">Tahun Jabatan</label>
                                        <input type="date" class="form-control @error('tahun_jabatan') is-invalid @enderror"
                                            name="tahun_jabatan" value="{{$asrama->tahun_jabatan}}">
                                    </div>
                                    @error('tahun_jabatan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direktur">Nama Direktur</label>
                                        <input type="text" class="form-control @error('direktur') is-invalid @enderror"
                                            name="direktur" value="{{$asrama->direktur}}">
                                    </div>
                                    @error('direktur')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ketua">Nama Direktur</label>
                                        <input type="text" class="form-control @error('ketua') is-invalid @enderror"
                                            name="ketua" value="{{$asrama->ketua}}">
                                    </div>
                                    @error('ketua')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
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