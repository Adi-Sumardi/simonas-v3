@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Data Kegiatan Akademik</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/akademik/update/{{$akademik->id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label><span
                                    class="text-danger">
                            <input value="{{$akademik->nama_warga}}" type="text" class="form-control" name="nama_warga">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Akademik:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control">
                                <option value="{{$akademik->komponen}}">{{$akademik->komponen}}</option>
                                <option value="Mendapatkan nilai (prestasi) akademik">Mendapatkan nilai (prestasi) akademik</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Mengikuti forum akademik">Mengikuti forum akademik</option>
                                <option value="Membaca buku atau artikel dll">Membaca buku atau artikel dll</option>
                                <option value="Memanfaatkan TIK untuk pengembangan diri">Memanfaatkan TIK untuk pengembangan diri</option>
                                <option value="Menulis makalah, artikel dll">Menulis makalah, artikel dll</option>
                                <option value="Menyampaikan gagasan, presentasi, moderator">Menyampaikan gagasan, presentasi, moderator</option>
                                <option value="Memberikan kontribusi (mengajar, melatih, membimbing)">Memberikan kontribusi (mengajar, melatih, membimbing)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                class="text-danger">
                            <input value="{{$akademik->kegiatan}}" type="text" class="form-control" name="kegiatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan:</label><span
                                class="text-danger">
                            <input value="{{$akademik->waktu}}" type="date" class="form-control" name="waktu">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Tempat Kegiatan:</label><span
                                class="text-danger">
                            <input value="{{$akademik->tempat}}" type="text" class="form-control" name="tempat">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan" class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                            <textarea value="{{$akademik->keterangan}}" type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file" class="control-label">Lampiran File:</label><span
                                    class="text-danger">
                            <input value="{{$akademik->file}}" type="file" class="form-control" name="file">
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