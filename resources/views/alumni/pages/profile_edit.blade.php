@extends('alumni.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Profile User</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Edit Profile User</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/alumni/profile/data/update/{{$user->id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap:</label><span
                                    class="text-danger">*</span>
                            <input value="{{$user->name}}" type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama"
                                   class="control-label">Asrama:</label><span
                                    class="text-danger">
                            <select name="asrama" class="form-control {{ $errors->has('asrama') ? ' is-invalid': '' }}">
                                <option value="">-Pilih Asrama-</option>
                                <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                <option value="Asrama Putri">Asrama Putri</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('asrama') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_masuk">Tanggal Masuk Asrama:</label><span
                                    class="text-danger">*</span>
                            <input value="{{$user->tgl_masuk}}" type="date" class="form-control" name="tgl_masuk">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_keluar">Tanggal Keluar Asrama:</label><span
                                    class="text-danger">(Diisi ketika sudah menjadi ALUMNI)</span>
                            <input value="{{$user->tgl_keluar}}" type="date" class="form-control" name="tgl_keluar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat">Alamat Asal:</label><span
                                    class="text-danger">
                            <textarea value="{{$user->alamat}}" type="text" class="form-control" name="alamat"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp">Nomor Telpon:</label><span
                                    class="text-danger">
                            <input value="{{$user->no_telp}}" type="text" class="form-control" name="no_telp">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_sekarang">Alamat Sekarang:</label><span
                                    class="text-danger">(Diisi ketika sudah menjadi ALUMNI)</span>
                            <input value="{{$user->alamat_sekarang}}" type="text" class="form-control" name="alamat_sekarang">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan"
                                   class="control-label">Pekerjaan:</label><span
                                    class="text-danger">(Diisi ketika sudah menjadi ALUMNI)</span>
                            <select name="pekerjaan" class="form-control {{ $errors->has('pekerjaan') ? ' is-invalid': '' }}">
                                <option value="">-Pilih Pekerjaan-</option>
                                <option value="Profesional Bidang">Profesional Bidang</option>
                                <option value="Guru/Dosen/Peneliti">Guru/Dosen/Peneliti</option>
                                <option value="Karyawan Swasta/ASN">Karyawan Swasta/ASN</option>
                                <option value="Wiraswasta/Pengusaha">Wiraswasta/Pengusaha</option>
                                <option value="Pejabat Publik/Politisi/LSM">Pejabat Publik/Politisi/LSM</option>
                                <option value="Pensiunan/Pekerja Sosial">Pensiunan/Pekerja Sosial</option>
                                <option value="Lainnya">Lainnya..</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('pekerjaan') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir:</label><span
                                    class="text-danger">
                            <input value="{{$user->tgl_lahir}}" type="date" class="form-control" name="tgl_lahir">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asal_sekolah">Asal Sekolah:</label><span
                                    class="text-danger">
                            <input value="{{$user->asal_sekolah}}" type="text" class="form-control" name="asal_sekolah">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="universitas">Universitas:</label><span
                                    class="text-danger">
                            <input value="{{$user->universitas}}" type="text" class="form-control" name="universitas">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fakultas">Fakultas:</label><span
                                    class="text-danger">
                            <input value="{{$user->fakultas}}" type="text" class="form-control" name="fakultas">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prodi">Program Studi:</label><span
                                    class="text-danger">
                            <input value="{{$user->prodi}}" type="text" class="form-control" name="prodi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan">Angkatan:</label><span
                                    class="text-danger">
                            <input value="{{$user->angkatan}}" type="text" class="form-control" name="angkatan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_seminar">Tanggal Seminar Proposal:</label><span
                                    class="text-danger">
                            <input value="{{$user->tgl_seminar}}" type="date" class="form-control" name="tgl_seminar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_skripsi">Tanggal Skripsi:</label><span
                                    class="text-danger">
                            <input value="{{$user->tgl_skripsi}}" type="date" class="form-control" name="tgl_skripsi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_wisuda">Tanggal Wisuda:</label><span
                                    class="text-danger">
                            <input value="{{$user->tgl_wisuda}}" type="date" class="form-control" name="tgl_wisuda">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nik">NIK:</label><span
                                    class="text-danger">
                            <input value="{{$user->nik}}" type="text" class="form-control" name="nik">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prestasi">Prestasi:</label><span
                                    class="text-danger">
                            <textarea value="{{$user->prestasi}}" type="text" class="form-control" name="prestasi"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="organisasi">Organisasi:</label><span
                                    class="text-danger">
                            <textarea value="{{$user->organisasi}}" type="text" class="form-control" name="organisasi"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ayah">Nama Ayah:</label><span
                                    class="text-danger">
                            <input value="{{$user->nama_ayah}}" type="text" class="form-control" name="nama_ayah">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu:</label><span
                                    class="text-danger">
                            <input value="{{$user->nama_ibu}}" type="text" class="form-control" name="nama_ibu">
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