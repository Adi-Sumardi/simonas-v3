@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Periode Penilaian Warga Asrama YAPI</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Periode Penilaian</h4></div>

        <div class="card-body pt-1">
            {{-- <form method="POST" action="/periode/store" enctype="multipart/form-data">
                @csrf --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="daterange">Waktu Periode:</label><br>
                            <input type="text" name="daterange" value="01/01/2021 - 01/02/2021" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_periode">Nama Periode:</label>
                            <input id="nama_periode" type="text" class="form-control" name="nama_periode"
                            value="">
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection