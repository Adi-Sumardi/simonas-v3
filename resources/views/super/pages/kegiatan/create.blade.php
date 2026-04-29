@extends('super.layouts.master')

@section('title')
    Kegiatan Asrama Create
@endsection

@section('css')

    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

    @component('super.common-components.breadcrumb')
        @slot('title')
        Kegiatan Asrama Create
        @endslot
        @slot('title_li')
        Kegiatan Asrama Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <form method="POST" action="/super-kegiatan-asrama-store" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_kegiatan">Nama Kegiatan:</label>
                                        <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                            name="nama_kegiatan">
                                    </div>
                                    @error('nama_kegiatan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tujuan">Tujuan Kegiatan</label>
                                        <input type="text" class="form-control @error('tujuan') is-invalid @enderror"
                                            name="tujuan">
                                    </div>
                                    @error('tujuan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="penyelenggara"
                                            class="control-label">Penyelenggara Kegiatan:</label><span
                                            class="text-danger">
                                        <select name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror">
                                            <option>- Pilih Penyelenggara -</option>
                                            <option value="YAPI">YAPI</option>
                                            <option value="Direktorat Keasramaan">Direktorat Keasramaan</option>
                                            <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Jati</option>
                                            <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                            <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                            <option value="Asrama Putri">Asrama Putri</option>
                                        </select>
                                    </div>
                                    @error('penyelenggara')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_kegiatan">Jenis Kegiatan</label>
                                        <select class="form-control @error('jenis_kegiatan') is-invalid @enderror"
                                            name="jenis_kegiatan">
                                            <option>- Pilih Program Kerja -</option>
                                            <option value="Program Kerja">Program Kerja</option>
                                            <option value="Non-Program Kerja">Non-Program Kerja</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    @error('jenis_kegiatan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu">Waktu Kegiatan</label>
                                        <input type="date" class="form-control @error('waktu') is-invalid @enderror"
                                            name="waktu">
                                    </div>
                                    @error('waktu')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tempat">Tempat Kegiatan</label>
                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                            name="tempat">
                                    </div>
                                    @error('tempat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keterangan">Uraian Kegiatan</label>
                                        <textarea rows="1" class="form-control @error('keterangan') is-invalid @enderror"
                                            name="keterangan"></textarea>
                                    </div>
                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file">file Kegiatan</label>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                            name="file">
                                    </div>
                                    @error('file')
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

@section('script')

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js')}}"></script>

    <!-- Select2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Ambil dari atribut data komponen
        $(document).ready(function() {
            $('#data_komponen').select2();
            $('#data_komponen').on('change', function() {
                const selected = $(this).find('option:selected');
                const jum = selected.data('id');

                $("#data").val(jum);
            });
        });
    </script>

@endsection