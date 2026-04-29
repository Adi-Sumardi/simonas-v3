@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Kegiatan Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/kegiatan-store" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kegiatan">Nama Kegiatan:</label><span
                                    class="text-danger">*</span>
                            <input id="nama_kegiatan" type="text"
                                   class="form-control{{ $errors->has('nama_kegiatan') ? ' is-invalid' : '' }}"
                                   name="nama_kegiatan"
                                   tabindex="1" placeholder="Masukan nama kegiatan" value="{{ old('nama_kegiatan') }}"
                                   autofocus required>
                            <div class="invalid-feedback">
                                {{ $errors->first('nama_kegiatan') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tujuan"
                                   class="control-label">Tujuan Kegiatan:</label><span
                                    class="text-danger">
                                <textarea id="tujuan" type="keterangan" placeholder="Keterangan Kegiatan"
                                        class="form-control{{ $errors->has('tujuan') ? ' is-invalid': '' }}"
                                        name="tujuan" tabindex="2"></textarea>
                            <div class="invalid-feedback">
                                {{ $errors->first('tujuan') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="penyelenggara"
                                   class="control-label">Penyelenggara:</label><span
                                    class="text-danger">*</span>
                            <select name="penyelenggara" class="form-control {{ $errors->has('penyelenggara') ? ' is-invalid': '' }}">
                                <option value="Direktorat Keasramaan">Direktorat Keasramaan</option>
                                <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Jati</option>
                                <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                <option value="Asrama Daarul Quran Fatahilah">Asrama Daarul Quran Fatahilah</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('penyelenggara') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_kegiatan"
                                   class="control-label">Jenis Kegiatan:</label><span
                                    class="text-danger">*</span>
                            <select name="jenis_kegiatan" class="form-control {{ $errors->has('jenis_kegiatan') ? ' is-invalid': '' }}">
                                <option value="Program Kerja">Program Kerja</option>
                                <option value="Non-Program Kerja">Non-Program Kerja</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('jenis_kegiatan') }}
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
                                    class="text-danger">*</span>
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
                            <label for="role"
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
                                   class="control-label">Lampiran File: (max. file 1 MB)</label>
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