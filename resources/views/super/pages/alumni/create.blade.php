@extends('super.layouts.master')

@section('title')
    Alumni Asrama Create
@endsection

@section('css')

    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title')
        Alumni Asrama Create
        @endslot
        @slot('title_li')
        Alumni Asrama Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <H4>Data Pribadi</H4>
                        <form method="POST" action="/super-alumni-asrama-store" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="alumni_academic" value="" id="alumni_academic" />
                            <input type="hidden" name="alumni_organization" value="" id="alumni_organization" />
                            <input type="hidden" name="alumni_job_history" value="" id="alumni_job_history" />
                            <input type="hidden" name="alumni_achievement" value="" id="alumni_achievement" />
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Alumni</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            name="nama" required>
                                    </div>
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nia">NIA</label>
                                        <input type="text" class="form-control @error('nia') is-invalid @enderror"
                                            name="nia" required>
                                    </div>
                                    @error('nia')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" required>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto">FOTO</label>
                                        <input type="file" name="foto" accept="image/*" required>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_whatsapp">No. HP/Whatsapp</label>
                                        <input type="tel" class="form-control @error('no_whatsapp') is-invalid @enderror"
                                            name="no_whatsapp" required>
                                    </div>
                                    @error('no_whatsapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provinsi_asal">Tempat Lahir</label>
                                        <input type="text" class="form-control @error('provinsi_asal') is-invalid @enderror"
                                            name="provinsi_asal">
                                    </div>
                                    @error('provinsi_asal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            name="tanggal_lahir">
                                    </div>
                                    @error('tanggal_lahir')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provinsi"
                                            class="control-label">Provinsi</label><span
                                            class="text-danger">
                                        <select name="id_province" class="form-control {{ $errors->has('id_province') ? ' is-invalid': '' }}">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach ($province as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    @error('id_province')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_regency"
                                            class="control-label">Kota/Kabupaten</label><span
                                            class="text-danger">
                                        <select name="id_district" class="form-control {{ $errors->has('id_district') ? ' is-invalid': '' }}">
                                            <option value="">Pilih Kecamatan</option>
                                            @if(isset($selectedProvince)) <!-- Cek apakah provinsi sudah dipilih -->
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district }}">{{ $district }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @error('id_regency')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat_domisili">Alamat Lengkap</label>
                                        <textarea class="form-control @error('alamat_domisili') is-invalid @enderror" name="alamat_domisili"></textarea>
                                    </div>
                                    @error('alamat_domisili')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="number" class="form-control @error('kode_pos') is-invalid @enderror"
                                            name="kode_pos">
                                    </div>
                                    @error('kode_pos')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <br/>

                            <H4>Data Asrama</H4>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_masuk_asrama">Tahun Masuk</label>
                                        <input type="number" class="form-control @error('tahun_masuk_asrama') is-invalid @enderror"
                                            name="tahun_masuk_asrama" max="{{Date('Y')}}">
                                    </div>
                                    @error('tahun_masuk_asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_keluar_asrama">Tahun Keluar</label>
                                        <input type="number" class="form-control @error('tahun_keluar_asrama') is-invalid @enderror"
                                            name="tahun_keluar_asrama" max="{{Date('Y')}}">
                                    </div>
                                    @error('tahun_keluar_asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Daftar Asrama"
                                            class="control-label">Daftar Asrama</label><span
                                            class="text-danger">
                                        <select name="id_asrama" class="form-control {{ $errors->has('id_asrama') ? ' is-invalid': '' }}" required>
                                            <option value="">Pilih Asrama</option>
                                            @foreach ($asrama as $row)
                                            <option value="{{ $row->id }}">{{ $row->nama_asrama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('id_asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan_asrama">Jabatan Terakhir</label>
                                        <input type="text" class="form-control @error('jabatan_asrama') is-invalid @enderror"
                                            name="jabatan_asrama">
                                    </div>
                                    @error('jabatan_asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="teman_angkatan">Teman 1 Angkatan</label>
                                        <input type="text" class="form-control @error('teman_angkatan') is-invalid @enderror"
                                            name="teman_angkatan">
                                    </div>
                                    @error('teman_angkatan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <span class="invalid-feedback" style="display: block; color: #a0a0a0">
                                        <strong>Tulis Nama Teman lebih dari 1, pisahkan dengan @ (Tag)</strong>
                                    </span>
                                </div>
                            </div>

                            <br/>

                            <H4>Riwayat Pendidikan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-academic">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-academic datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jenjang Pendidikan</th>
                                            <th>Nama Universitas</th>
                                            <th>Fakultas/Jurusan</th>
                                            <th>Tahun Masuk-Lulus</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Pengalaman Organisasi</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal"      data-target=".modal-organization">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-organization datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jenis Organisasi</th>
                                            <th>Nama Organisasi</th>
                                            <th>Jabatan</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Riwayat Pekerjaan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-job">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-job datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Jabatan</th>
                                            <th>Nama Perusahaan</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Riwayat Prestasi/Penghargaan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-achievement">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-achievement datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Prestasi atau Penghargaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-center modal-academic" tabindex="-1" role="dialog" aria-labelledby="input-academic" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Pendidikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="gelar" class="control-label">Jenjang Pendidikan</label>
                            <select name="gelar" class="form-control">
                                <option value="S1">S1 - Sarjana</option>
                                <option value="S2">S2 - Magister</option>
                                <option value="S3">S3 - Doktor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_kampus">Nama Universitas</label>
                            <input type="text" class="form-control" name="nama_kampus">
                        </div>
                        <div class="form-group">
                            <label for="fakultas_jurusan">Fakultas / Jurusan</label>
                            <input type="text" class="form-control" name="fakultas_jurusan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Masuk - Lulus</label>
                            <input type="text" class="form-control" name="tahun_ajaran" placeholder="contoh : 2013-2017">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-academic')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-organization" tabindex="-1" role="dialog" aria-labelledby="input-organization" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Organisasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="type_organization" class="control-label">Jenis Organisasi</label>
                            <select name="type_organization" class="form-control">
                                <option value="Kemahasiswaan">Kemahasiswaan</option>
                                <option value="Kemasyarakatan">Kemasyarakatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_organisasi">Nama Organisasi</label>
                            <input type="text" class="form-control" name="nama_organisasi">
                        </div>
                        <div class="form-group">
                            <label for="organisasi_jabatan">Jabatan</label>
                            <input type="text" class="form-control" name="organisasi_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_organisasi">Tahun</label>
                            <input type="text" class="form-control" name="tahun_organisasi" placeholder="contoh tahun 2001-2002">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-organization')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-job" tabindex="-1" role="dialog" aria-labelledby="input-job" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Pekerjaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="nama_jabatan">Nama jabatan</label>
                            <input type="text" class="form-control" name="nama_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="bidang_pekerjaan">Nama Perusahaan</label>
                            <input type="text" class="form-control" name="bidang_pekerjaan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_kerjaan">Tahun </label>
                            <input type="text" class="form-control" name="tahun_kerjaan" placeholder="Contoh tahun 2001-2002">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-job')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-achievement" tabindex="-1" role="dialog" aria-labelledby="input-achievement" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Prestasi/Penghargaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="in-" class="form-group">
                        <div class="form-group">
                            <label for="nama_penghargaan">Prestasi/Penghargaan</label>
                            <input type="text" class="form-control" name="nama_penghargaan">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-achievement')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('script')

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js')}}"></script>

    <script>
        var dt_academic = $(".dt-academic").DataTable();
        var dt_organization = $(".dt-organization").DataTable();
        var dt_job = $(".dt-job").DataTable();
        var dt_achievement = $(".dt-achievement").DataTable();

        var renderTableAcademic = function() {
            dt_academic.clear().draw();
            var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
            val_academic.forEach((val, i) => {
                dt_academic.row.add([
                    i+1,
                    val.gelar,
                    val.nama_kampus,
                    val.fakultas_jurusan,
                    val.tahun_ajaran,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delAcademic(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_academic.draw();
        }

        var renderTableOrganization = function() {
            dt_organization.clear().draw();
            var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
            val_organization.forEach((val, i) => {
                dt_organization.row.add([
                    i+1,
                    val.type_organization,
                    val.nama_organisasi,
                    val.organisasi_jabatan,
                    val.tahun_organisasi,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delOrganization(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_organization.draw();
        }

        var renderTableJob = function() {
            dt_job.clear().draw();
            var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
            val_job_history.forEach((val, i) => {
                dt_job.row.add([
                    i+1,
                    val.nama_jabatan,
                    val.bidang_pekerjaan,
                    val.tahun_kerjaan,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delJob(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_job.draw();
        }

        var renderTableachievement = function() {
            dt_achievement.clear().draw();
            var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
            console.log({val_achievement})
            val_achievement.forEach((val, i) => {
                dt_achievement.row.add([
                    i+1,
                    val.nama_penghargaan,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delAchievement(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_achievement.draw();
        }

        var delAcademic = function(index) {
            var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
            // dt_academic.row(index).remove().draw();
            console.log(val_academic);
            val_academic.splice(index, 1);
            console.log(val_academic);
            $("#alumni_academic").val(JSON.stringify(val_academic));
            renderTableAcademic();

        }
        var delOrganization = function(index) {
            var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
            // dt_organization.row(index).remove().draw();
            console.log(val_organization);
            val_organization.splice(index, 1);
            console.log(val_organization);
            $("#alumni_organization").val(JSON.stringify(val_organization));
            renderTableOrganization();

        }
        var delJob = function(index) {
            var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
            // dt_job.row(index).remove().draw();
            console.log(val_job_history);
            val_job_history.splice(index, 1);
            console.log(val_job_history);
            $("#alumni_job_history").val(JSON.stringify(val_job_history));
            renderTableJob();

        }
        var delAchievement = function(index) {
            var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
            // dt_achievement.row(index).remove().draw();
            console.log(val_achievement);
            val_achievement.splice(index, 1);
            $("#alumni_achievement").val(JSON.stringify(val_achievement));
            console.log("DARI HTML", $("#alumni_achievement").val());
            renderTableachievement();

        }

        var saveData = function(className) {
            var serialize = $(className).find("input").serializeArray();
            var serializeOption = $(className).find("select").serializeArray();
            serialize = serialize.concat(serializeOption);
            var data = {};
            serialize.forEach((val, i) => {
                data[val.name] = val.value;
            });
            data['id'] = '';
            switch (className) {
                case ".modal-academic":
                    var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
                    val_academic.push(data);
                    console.log(val_academic);
                    $("#alumni_academic").val(JSON.stringify(val_academic));
                    renderTableAcademic();
                    break;
                case ".modal-organization":
                    var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
                    val_organization.push(data);
                    $("#alumni_organization").val(JSON.stringify(val_organization));
                    renderTableOrganization();
                    break;
                case ".modal-job":
                    var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
                    val_job_history.push(data);
                    $("#alumni_job_history").val(JSON.stringify(val_job_history));
                    renderTableJob();
                    break;
                case ".modal-achievement":
                    var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
                    console.log(val_achievement);
                    val_achievement.push(data);
                    $("#alumni_achievement").val(JSON.stringify(val_achievement));
                    renderTableachievement();
                    break;
            }
            $('.modal-academic').modal('hide');
            $('.modal-organization').modal('hide');
            $('.modal-job').modal('hide');
            $('.modal-achievement').modal('hide');
        }

        $(document).ready(function () {
            var regency = @json($regency);

            $('.modal-academic').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
                $(this).find("select").val("S1");
            });
            $('.modal-organization').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
                $(this).find("select").val("O1");
            });
            $('.modal-job').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
            });
            $('.modal-achievement').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
            });

            renderTableAcademic();
            renderTableOrganization();
            renderTableJob();
            renderTableachievement();
        });


    </script>
    
    <script>
        $(document).ready(function () {
            // Deteksi perubahan pada dropdown provinsi
            $('select[name="id_province"]').on('change', function () {
                var selectedProvince = $(this).val();

                // Lakukan permintaan AJAX untuk mengambil kecamatan berdasarkan provinsi yang dipilih
                $.ajax({
                    url: '/get-districts',
                    type: 'GET',
                    data: { province: selectedProvince },
                    success: function (data) {
                        $('select[name="id_district"]').empty();
                        $('select[name="id_district"]').append('<option value="">Pilih Kecamatan</option>');
                        $.each(data, function (key, value) {
                            $('select[name="id_district"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        var userId = {{ auth()->user()->id }};
        console.log(userId)
    </script>

@endsection
