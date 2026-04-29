@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Kegiatan Akademik</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/akademik/store" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label>
                            <input id="nama_warga" readonly type="text" class="form-control" name="nama_warga"
                            value="{{Auth::user()->name}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Akademik:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control {{ $errors->has('komponen') ? ' is-invalid': '' }}">
                                <option value="">-Pilih Kompetensi-</option>
                                <option value="Mendapatkan nilai (prestasi) akademik">Mendapatkan nilai (prestasi) akademik</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Mengikuti forum akademik">Mengikuti forum akademik</option>
                                <option value="Membaca buku atau artikel dll">Membaca buku atau artikel dll</option>
                                <option value="Memanfaatkan TIK untuk pengembangan diri">Memanfaatkan TIK untuk pengembangan diri</option>
                                <option value="Menulis makalah, artikel dll">Menulis makalah, artikel dll</option>
                                <option value="Menyampaikan gagasan, presentasi, moderator">Menyampaikan gagasan, presentasi, moderator</option>
                                <option value="Memberikan kontribusi (mengajar, melatih, membimbing)">Memberikan kontribusi (mengajar, melatih, membimbing)</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('komponen') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label><span
                                    class="text-danger">
                            <input id="kegiatan" type="text"
                                   class="form-control{{ $errors->has('kegiatan') ? ' is-invalid' : '' }}"
                                   placeholder="Masukan Nama Kegiatan" name="kegiatan" tabindex="1"
                                   value="{{ old('kegiatan') }}"
                                   required autofocus>
                            <div class="invalid-feedback">
                                {{ $errors->first('kegiatan') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu" class="control-label">Waktu Kegiatan
                                :</label><span
                                    class="text-danger">
                            <input id="waktu" type="date"
                                   class="form-control{{ $errors->has('waktu') ? ' is-invalid': '' }}"
                                   placeholder="Masukan Waktu Kegiatan" name="waktu" tabindex="2" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('waktu') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat"
                                   class="control-label">Tempat Kegiatan:</label><span
                                    class="text-danger">
                            <input id="tempat" type="text" placeholder="Masukan Tempat Kegiatan"
                                   class="form-control{{ $errors->has('tempat') ? ' is-invalid': '' }}"
                                   name="tempat" tabindex="2">
                            <div class="invalid-feedback">
                                {{ $errors->first('tempat') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan"
                                   class="control-label">Uraian Kegiatan:</label><span
                                    class="text-danger">
                                <textarea id="keterangan" type="keterangan" placeholder="Keterangan Kegiatan"
                                        class="form-control{{ $errors->has('keterangan') ? ' is-invalid': '' }}"
                                        name="keterangan" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                {{ $errors->first('keterangan') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file"
                                   class="control-label">Lampiran File:</label><span
                                    class="text-danger">
                                <input id="file" type="file" placeholder="Keterangan Kegiatan"
                                        class="form-control{{ $errors->has('file') ? ' is-invalid': '' }}"
                                        name="file" tabindex="2">
                            <div class="invalid-feedback">
                                {{ $errors->first('file') }}
                            </div>
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