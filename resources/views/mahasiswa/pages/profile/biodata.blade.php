    @extends('mahasiswa.layouts.master-without-nav')

    @section('title')
       Biodata
    @endsection
    
    @section('css')
        <!-- DataTables -->
        <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
        <!-- Select2-->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
    @endsection
    
    @section('content')

        <div class="container">
            <div class="card">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form action="/mahasiswa-data-info-update/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="form-group">
                                    <h5 style="text-align: center" class="mb-4"><strong>DATA PERSONAL</strong></h5>
                                    <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="asrama">Asrama:</label>
                                                    <select class="form-control @error('asrama') is-invalid @enderror"
                                                        name="asrama">
                                                        <option value="{{ Auth::user()->asrama}}">{{ Auth::user()->asrama}}</option>
                                                        <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                                        <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                                        <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                                        <option value="Asrama Putri">Asrama Putri</option>
                                                    </select>
                                                </div>
                                                @error('asrama')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_induk">Nomor Induk Warga<span style="color: red">*</span>:</label>
                                                    <input type="number" class="form-control @error('no_induk') is-invalid @enderror"
                                                        name="no_induk" value="{{ Auth::user()->no_induk}}">
                                                </div>
                                                @error('no_induk')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_masuk">Tanggal Masuk<span style="color: red">*</span>:</label>
                                                    <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror"
                                                        name="tgl_masuk" value="{{ Auth::user()->tgl_masuk}}">
                                                </div>
                                                @error('tgl_masuk')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nik">NIK<span style="color: red">*</span>:</label>
                                                    <input type="number" class="form-control @error('nik') is-invalid @enderror"
                                                        name="nik" value="{{ Auth::user()->nik}}">
                                                </div>
                                                @error('nik')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_lahir">Tanggal Lahir<span style="color: red">*</span>:</label>
                                                    <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                        name="tgl_lahir" value="{{ Auth::user()->tgl_lahir}}">
                                                </div>
                                                @error('tgl_lahir')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="alamat">Alamat Jalan<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                                        name="alamat" value="{{ Auth::user()->alamat}}">
                                                </div>
                                                @error('alamat')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kecamatan">Kecamatan<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                                        name="kecamatan" value="{{ Auth::user()->kecamatan}}">
                                                </div>
                                                @error('kecamatan')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kota">Kota/Kabupaten<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                                        name="kota" value="{{ Auth::user()->kota}}">
                                                </div>
                                                @error('kota')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="provinsi">Provinsi<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('provinsi') is-invalid @enderror"
                                                        name="provinsi" value="{{ Auth::user()->provinsi}}">
                                                </div>
                                                @error('provinsi')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="kode_pos">Kode POS<span style="color: red">*</span>:</label>
                                                    <input type="number" class="form-control @error('kode_pos') is-invalid @enderror"
                                                        name="kode_pos" value="{{ Auth::user()->kode_pos}}">
                                                </div>
                                                @error('kode_pos')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="no_telp">Nomor Telepon<span style="color: red">*</span>:</label>
                                                    <input type="number" class="form-control @error('no_telp') is-invalid @enderror"
                                                        name="no_telp" value="{{ Auth::user()->no_telp}}">
                                                </div>
                                                @error('no_telp')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="asal_sekolah">Asal Sekolah<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                                        name="asal_sekolah" value="{{ Auth::user()->asal_sekolah}}">
                                                </div>
                                                @error('asal_sekolah')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_ayah">Nama Ayah<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                                        name="nama_ayah" value="{{ Auth::user()->nama_ayah}}">
                                                </div>
                                                @error('nama_ayah')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nama_ibu">Nama Ibu<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                                        name="nama_ibu" value="{{ Auth::user()->nama_ibu}}">
                                                </div>
                                                @error('nama_ibu')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5 style="text-align: center" class="mb-4"><strong>DATA PENDIDIKAN</strong></h5>
                                    <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                    <div class="form-group">
                                        <div class="row">                
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="universitas">Universitas<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('universitas') is-invalid @enderror"
                                                        name="universitas" value="{{ Auth::user()->universitas}}">
                                                </div>
                                                @error('universitas')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fakultas">Fakultas<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('fakultas') is-invalid @enderror"
                                                        name="fakultas" value="{{ Auth::user()->fakultas}}">
                                                </div>
                                                @error('fakultas')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prodi">Program Studi<span style="color: red">*</span>:</label>
                                                    <input type="text" class="form-control @error('prodi') is-invalid @enderror"
                                                        name="prodi" value="{{ Auth::user()->prodi}}">
                                                </div>
                                                @error('prodi')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="angkatan">Tahun Angkatan<span style="color: red">*</span>:</label>
                                                    <input type="number" class="form-control @error('angkatan') is-invalid @enderror"
                                                        name="angkatan" value="{{ Auth::user()->angkatan}}">
                                                </div>
                                                @error('angkatan')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_seminar">Tanggal Seminar Skripsi:</label>
                                                    <input type="date" class="form-control @error('tgl_seminar') is-invalid @enderror"
                                                        name="tgl_seminar" value="{{ Auth::user()->tgl_seminar}}">
                                                </div>
                                                @error('tgl_seminar')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_skripsi">Tanggal Sidang Skripsi:</label>
                                                    <input type="date" class="form-control @error('tgl_skripsi') is-invalid @enderror"
                                                        name="tgl_skripsi" value="{{ Auth::user()->tgl_skripsi}}">
                                                </div>
                                                @error('tgl_seminar')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tgl_wisuda">Tanggal Wisuda:</label>
                                                    <input type="date" class="form-control @error('tgl_wisuda') is-invalid @enderror"
                                                        name="tgl_wisuda" value="{{ Auth::user()->tgl_wisuda}}">
                                                </div>
                                                @error('tgl_wisuda')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="prestasi">Prestasi:</label>
                                                    <input type="text" class="form-control @error('prestasi') is-invalid @enderror"
                                                        name="prestasi" value="{{ Auth::user()->prestasi}}">
                                                </div>
                                                @error('prestasi')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="organisasi">Organisasi<span style="color: red">*</span>:</label>
                                                    {{--  <input type="text" class="tm-input tm-input-info form-control @error('organisasi') is-invalid @enderror"  --}}
                                                    <input type="text" class="form-control @error('organisasi') is-invalid @enderror"
                                                        name="organisasi" value="{{ Auth::user()->organisasi}}">
                                                </div>
                                                @error('organisasi')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    @endsection
    
    @section('script')
        <!-- Required datatable js -->
        <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
        <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    
        <!-- Datatable init js -->
        <script src="{{ URL::asset('/js/pages/datatables.init.js') }}"></script>
    
        <!-- apexcharts -->
        <script src="{{ URL::asset('/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ URL::asset('/js/pages/profile.init.js') }}"></script>
    
        <!-- apexcharts init -->
        <script src="{{ URL::asset('/js/pages/apexcharts.init.js') }}"></script>
    
        <!-- Select2-->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
        <!-- Sweet alert init js-->
        <script src="{{ URL::asset('/js/pages/sweet-alerts.init.js') }}"></script>
    
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.js"></script>
    
    @endsection
    


