@extends('super.layouts.master')

@section('title') Reporting @endsection

@section('content')

    @component('super.common-components.breadcrumb')
         @slot('title') Reporting Kegiatan Warga  @endslot
         @slot('title_li') Simonas   @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3"><strong>FILTER JUMLAH KEGIATAN</strong></div>
                    <form action="{{ route('super-kegiatan-reporting-filterDate') }}" method="GET">
                        <div style="background-color: rgba(200, 200, 255, 0.249)" class="row justify-content-center pt-3 pb-3">
                            <div class="col-md-3 col-6">
                                <label for="startDate">Tanggal Awal</label>
                                <input type="text" name="startDate" id="startDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="endDate">Tanggal Akhir</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-1 col-12 mt-2 align-self-end">
                                <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js')}}"></script>
    
    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js')}}"></script>
    
    <!-- Calendar init -->
    <script src="{{ URL::asset('js/pages/dashboard.init.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{URL::asset('/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- apexcharts init -->
    <script src="{{URL::asset('/js/pages/apexcharts.init.js')}}"></script>
    <script src="{{URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

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