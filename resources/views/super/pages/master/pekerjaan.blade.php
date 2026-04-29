@extends('super.layouts.master')

@section('title')
    Data Pekerjaan Create
@endsection

@section('css')

    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title')
        Data Pekerjaan Create
        @endslot
        @slot('title_li')
        Data Pekerjaan Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <H4>Master Pekerjaan</H4>
                        <br>
                        <form method="POST" action="/super-alumni-asrama-store" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Pekerjaan</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            name="nama" required>
                                    </div>
                                    @error('nama')
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


