@extends('super.layouts.master')

@section('title') Reporting @endsection

@section('content')

    @component('super.common-components.breadcrumb')
         @slot('title') Reporting Kegiatan Warga  @endslot
         @slot('title_li') Simonas   @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="text-right mb-3">
                <button class="btn btn-secondary" onclick="printCard()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3"><strong>REKAP JUMLAH KEGIATAN WARGA ASRAMA</strong></div>
                    <div class="row justify-content-center pt-3 pb-3">
                        <div class="col-md-3 col-6">
                            <label>Dari Tanggal</label>
                            <input readonly type="text" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3 col-6">
                            <label>Sampai Tanggal</label>
                            <input readonly type="text" class="form-control" value="{{ $endDate }}">
                        </div>
                    </div>
    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Asrama</th>
                                <th>Akademik</th>
                                <th>Leadership</th>
                                <th>Karakter Islami</th>
                                <th>Kreatifitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedData as $asrama => $items)
                                <tr>
                                    <td colspan="6" class="font-weight-bold">{{ $asrama }}</td>
                                </tr>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ $item['asrama'] }}</td>
                                        <td>{{ $item['jumlahAkademik'] }}</td>
                                        <td>{{ $item['jumlahLeadership'] }}</td>
                                        <td>{{ $item['jumlahKarakter'] }}</td>
                                        <td>{{ $item['jumlahKreatif'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
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

    <script>
        function printCard() {
            var printContents = document.querySelector('.card-body').innerHTML;
            var originalContents = document.body.innerHTML;
        
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

@endsection