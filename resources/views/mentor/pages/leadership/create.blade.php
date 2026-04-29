@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Leadership Warga Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Kegiatan Leadership</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/mentor/leadership/store" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6" hidden>
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label>
                            <input id="nama_warga" readonly type="text" class="form-control" name="nama_warga"
                            value="{{Auth::user()->name}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen"
                                   class="control-label">Komponen Kurikulum Leadership:</label><span
                                    class="text-danger">
                            <select name="komponen" class="form-control {{ $errors->has('komponen') ? ' is-invalid': '' }}">
                                <option value="">-Pilih Kompetensi-</option>
                                <option value="Mengikuti pelatihan kepemimpinan">Mengikuti pelatihan kepemimpinan</option>
                                <option value="Mengikuti kegiatan mentoring">Mengikuti kegiatan mentoring</option>
                                <option value="Melaksanakan tugas kepanitiaan (mandat)">Melaksanakan tugas kepanitiaan (mandat)</option>
                                <option value="Melakukan tugas sebagai pengurus organisasi">Melakukan tugas sebagai pengurus organisasi</option>
                                <option value="Menjadi peserta atau memimpin rapat">Menjadi peserta atau memimpin rapat</option>
                                <option value="Mengikuti diskusi atau debat penyelesaian masalah">Mengikuti diskusi atau debat penyelesaian masalah</option>
                                <option value="Menulis surat, proposal kegiatan, laporan dll">Menulis surat, proposal kegiatan, laporan dll</option>
                                <option value="Memberikan kontribusi baik harta, tenaga, waktu">Memberikan kontribusi baik harta, tenaga, waktu</option>
                                <option value="Menyampaikan gagasan baik lisan atau tulisan">Menyampaikan gagasan baik lisan atau tulisan</option>
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
                                   class="control-label">Lampiran File: (max. file 1 MB)</label><span
                                    class="text-danger">*</span>
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