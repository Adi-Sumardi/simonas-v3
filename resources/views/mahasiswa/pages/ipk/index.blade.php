@extends('mahasiswa.layouts.master-layouts')

@section('title')
 Data IPK
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
IPK
@endslot
@slot('li_1')
Pages
@endslot
@endcomponent

    <div class="row">
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="modal fade bs-ips-modal-center" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">IP Create</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/mahasiswa-ipk-store" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="semester">Semester:</label>
                                            <input type="text"
                                                class="form-control @error('semester') is-invalid @enderror"
                                                name="semester">
                                        </div>
                                        @error('semester')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tahun">Tahun:</label>
                                            <input type="text"
                                                class="form-control @error('tahun') is-invalid @enderror"
                                                name="tahun">
                                        </div>
                                        @error('tahun')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ip">IP: <span class="text-danger">*(Contoh Penulisan: 3.75)
                                                    Jangan Pake "Koma ," !!!</span></label>
                                            <input type="text"
                                                class="form-control @error('tahun') is-invalid @enderror" name="ip">
                                        </div>
                                        @error('ip')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file">File KHS: <span style="color: red">Support PDF
                                                    aja!</span></label>
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
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <h6>Data IPK</h6>
                                            <h4>@if ($data_ipks !== null)
                                                <h4 class="mb-0 badge badge-soft-success font-size-14">{{number_format($data_ipks, 2)}}
                                                </h4>
                                                @else
                                                <h4 class="mb-0 badge badge-soft-success font-size-14">0</h4>
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <a class="btn btn-primary text-white" data-toggle="modal"
                                            data-target=".bs-ips-modal-center">
                                            <i class="mdi mdi-plus font-size-16"></i> Add Data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div style="overflow: scroll">
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Semester ke-</th>
                                        <th scope="col">Tahun</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ipks as $item)

                                    <tr>
                                        <td>{{$item->semester}}</td>
                                        <td>{{$item->tahun}}</td>
                                        <td>{{$item->ip}}</td>
                                        <td>
                                            <a href="/mahasiswa-ipk-detail/{{$item->id}}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="mdi mdi-eye-circle-outline"></i> View</a>
                                            <a href="/mahasiswa-ipk-edit/{{$item->id}}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="mdi mdi-file-edit-outline"></i> Edit</a>
                                            <a href="/mahasiswa-ipk-khs-edit/{{$item->id}}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="mdi mdi-image-edit-outline"></i> Update KHS</a>
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
