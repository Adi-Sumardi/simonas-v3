@extends('mahasiswa.layouts.master-layouts')

@section('title')
Leadership
@endsection

@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Select2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
@endsection

@section('content')
@component('mahasiswa.common-components.breadcrumb')
@slot('title')
Leadership
@endslot
@slot('li_1')
Pages
@endslot
@endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/mahasiswa-data-leadership-store" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label for="nama_warga">Nama</label>
                                        <input type="text"
                                            class="form-control @error('nama_warga') is-invalid @enderror"
                                            name="nama_warga" value="{{Auth::user()->name}}">
                                    </div>
                                    @error('nama_warga')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6" hidden>
                                <div class="form-group">
                                    <label for="asrama">Asrama</label>
                                    <input type="text"
                                        class="form-control @error('asrama') is-invalid @enderror"
                                        name="asrama" value="{{Auth::user()->asrama}}">
                                </div>
                                @error('asrama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="komponen" class="mb-0 badge badge-soft-primary font-size-14 mb-3">Komponen</label>
                                    <select id="data_komponen_leadership" class="form-control @error('komponen') is-invalid @enderror" name="komponen" required>
                                        <option value="">- Pilih Komponen Kegiatan Leadership -</option>
                                        @foreach ($komponen_leaderships as $item)
                                        <option value="{{ $item->nama_komponen }}"
                                            data-kode="{{ $item->kode }}" data-id="{{ $item->id }}"
                                            data-aspek="{{ $item->aspek }}">
                                            {{ $item->kode }} - {{ $item->nama_komponen }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('komponen')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Ini harus diisi</strong>
                                    </span>
                                    @enderror
                                </div>
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
                                    <input type="text"
                                        class="form-control @error('kegiatan') is-invalid @enderror"
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
                                    <input type="date"
                                        class="form-control @error('waktu') is-invalid @enderror"
                                        name="waktu" value="{{ now()->format('Y-m-d') }}">
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
                                    <input type="text"
                                        class="form-control @error('tempat') is-invalid @enderror"
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
                                    <textarea rows="1"
                                        class="form-control @error('keterangan') is-invalid @enderror"
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
                                    <input type="file"
                                        class="form-control @error('file') is-invalid @enderror"
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
                            <button type="submit" class="btn btn-primary">Submit</button>
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

<script src="{{URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- Chart JS -->
<script src="{{URL::asset('/libs/chart-js/chart-js.min.js')}}"></script>
<script src="{{URL::asset('/js/pages/chartjs.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    $(document).ready(function (e) {


           $('#image').change(function(){

            let reader = new FileReader();

            reader.onload = (e) => {

              $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

           });

        });

</script>

<script>
    $(document).ready(function() {
            // Inisialisasi Select2 untuk semua dropdown
            $('#data_komponen_akademik, #data_komponen_leadership, #data_komponen_karakter, #data_komponen_kreatif').select2();

            // Event handler saat terjadi perubahan pada semua dropdown
            $('#data_komponen_akademik, #data_komponen_leadership, #data_komponen_karakter, #data_komponen_kreatif').on('change', function() {
                // Mendapatkan elemen yang dipilih
                const selectedOption = $(this).find('option:selected');

                // Mendapatkan nilai data-id dari elemen yang dipilih
                const komponenId = selectedOption.data('id');

                // Mengisi nilai komponen_id ke dalam input dengan ID data
                $("#data").val(komponenId);
            });
        });
</script>

<script type="text/javascript">
    $(".tm-input").tagsManager();
</script>

<script>
    $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $('#searchButton').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                filterTableByDateRange(startDate, endDate);
            } else {
                alert('Silahkan tanggal mulai dan tanggal akhirnya');
            }
        });
</script>

@endsection
