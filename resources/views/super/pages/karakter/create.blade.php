@extends('super.layouts.master')

@section('title')
    Kegiatan Karakter Create
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
        Kegiatan Karakter Create
        @endslot
        @slot('title_li')
        Kegiatan Karakter Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <form method="POST" action="/super-kegiatan-karakter-store" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="nama_warga">Nama</label>
                                        <input type="text" class="form-control @error('nama_warga') is-invalid @enderror"
                                            name="nama_warga" value="{{Auth::user()->name}}">
                                    </div>
                                    @error('nama_warga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="asrama">Asrama</label>
                                        <input type="text" class="form-control @error('asrama') is-invalid @enderror"
                                            name="asrama" value="{{Auth::user()->asrama}}">
                                    </div>
                                    @error('asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="komponen"
                                            class="control-label">Komponen Karakter Islami:</label><span
                                            class="text-danger">
                                        <select name="komponen" id="data_komponen" class="form-control {{ $errors->has('komponen') ? ' is-invalid': '' }}">
                                            <option>Pilih Komponen Karakter Islami</option>
                                            @foreach ($komponens as $item)
                                                <option value="{{ $item->nama_komponen }}"
                                                    data-kode="{{ $item->kode }}"
                                                    data-id="{{ $item->id }}" data-aspek="{{ $item->aspek }}">
                                                    {{ $item->kode }} - {{ $item->nama_komponen }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('komponen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label>ID Komponen</label>
                                        <input type="text" class="form-control" id="data" name="komponen_id"
                                            readonly></input>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kegiatan">Nama Kegiatan</label>
                                        <input type="text" class="form-control @error('kegiatan') is-invalid @enderror"
                                            name="kegiatan">
                                    </div>
                                    @error('kegiatan')
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