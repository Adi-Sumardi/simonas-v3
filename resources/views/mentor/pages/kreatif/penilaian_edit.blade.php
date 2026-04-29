@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Kreatifitas dan Wirausaha Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Kreatifitas dan Wirausaha</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/mentor/penilaian/kreatif/update/{{$kreatif->id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label><span
                                    class="text-danger">*</span>
                            <input value="{{$kreatif->nama_warga}}" type="text" class="form-control" name="nama_warga">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen">Komponen Kurikulum Kreativitas dan Kewirausahaan:</label><span
                                class="text-danger">*</span>
                            <input value="{{$kreatif->komponen}}" type="text" class="form-control" name="komponen">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                class="text-danger">*</span>
                            <input value="{{$kreatif->kegiatan}}" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">*</span>
                            <input value="{{$kreatif->waktu}}" type="text" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Nama Penilai:</label><span
                                class="text-danger">*</span>
                            <input value="{{$kreatif->nama_penilai}}" type="text" class="form-control" name="nama_penilai">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Nilai:</label><span
                                    class="text-danger">*</span>
                            <input value="{{$kreatif->nilai}}" type="text" class="form-control" name="nilai">
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