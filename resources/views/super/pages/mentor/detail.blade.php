@extends('super.layouts.master')

@section('title') Profile @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
   
    @component('super.common-components.breadcrumb')
         @slot('title') Profile  @endslot
         @slot('li_1') Pages  @endslot
     @endcomponent


                    <!-- start row -->
                    <div class="row">
                        <div class="col-md-12 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-widgets py-3">

                                        <div class="text-center">
                                            <div class="">
                                                <img src="{{ url('/data_photo/' . $user->avatar) }}" alt="" class="avatar-lg mx-auto img-thumbnail rounded-circle">
                                                <div class="online-circle"><i class="fas fa-circle text-success"></i></div>
                                            </div>

                                            <div class="mt-3 ">
                                                <a href="#" class="text-dark font-weight-medium font-size-16">{{$user->name}}</a>
                                                <p class="text-body mt-1 mb-1">{{$user->asrama}}</p>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Personal Information</h5>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Email</p>
                                        <h6 class="">{{$user->email}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Tanggal Lahir</p>
                                        <h6 class="">{{$user->tgl_lahir}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Nomor Telepon</p>
                                        <h6 class="">{{$user->no_telp}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Alamat Asal</p>
                                        <h6 class="">{{$user->alamat}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Kecamatan</p>
                                        <h6 class="">{{$user->kecamatan }}</h6>
                                    </div>
                
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Kota/Kabupaten</p>
                                        <h6 class="">{{$user->kota }}</h6>
                                    </div>
                
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Provinsi</p>
                                        <h6 class="">{{$user->provinsi }}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Kampus</p>
                                        <h6 class="">{{$user->universitas}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Fakultas</p>
                                        <h6 class="">{{$user->fakultas}}</h6>
                                    </div>

                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Program Studi</p>
                                        <h6 class="">{{$user->prodi}}</h6>
                                    </div>
                
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Organisasi</p>
                                        <h6 class="">{{$user->organisasi}}</h6>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 col-xl-9">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-5">Activity</h4>
                        
                                        <ul class="list-unstyled activity-wid">
                                            <li class="activity-list">
                                                <div class="activity-icon avatar-xs">
                                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="mdi mdi-calendar-edit"></i>
                                                    </span>
                                                </div>
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <h5 class="font-size-14">20 Jan <i class="mdi mdi-arrow-right text-primary align-middle ml-2"></i></h5>
                                                    </div>
                                                    <div class="media-body">
                                                        <div>
                                                            Responded to need “Volunteer Activities"
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="activity-list">
                                                <div class="activity-icon avatar-xs">
                                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="mdi mdi-account-multiple-outline"></i>
                                                    </span>
                                                </div>
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <h5 class="font-size-14">23 Jan <i class="mdi mdi-arrow-right text-primary align-middle ml-2"></i></h5>
                                                    </div>
                                                    <div class="media-body">
                                                        <div>
                                                            Added an interest “Volunteer Activities”
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="activity-list">
                                                <div class="activity-icon avatar-xs">
                                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </span>
                                                </div>
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <h5 class="font-size-14">24 Jan <i class="mdi mdi-arrow-right text-primary align-middle ml-2"></i></h5>
                                                    </div>
                                                    <div class="media-body">
                                                        <div>
                                                            Everyone realizes why a new common language.. <a href="#">Read more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="activity-list">
                                                <div class="activity-icon avatar-xs">
                                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="mdi mdi-account-multiple-outline"></i>
                                                    </span>
                                                </div>
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <h5 class="font-size-14">26 Jan <i class="mdi mdi-arrow-right text-primary align-middle ml-2"></i></h5>
                                                    </div>
                                                    <div class="media-body">
                                                        <div>
                                                            Joined the group “Boardsmanship Forum”
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                        
                                        <div class="text-center mt-4">
                                            <a href="#" class="btn btn-primary btn-sm">View more <i class="mdi mdi-arrow-right ml-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="d-print-none">
                        <div class="float-right">
                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>
                </div>

            <!-- end row -->
    @endsection

    @section('script')

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ URL::asset('/libs/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/profile.init.js')}}"></script>

    <!-- apexcharts init -->
    <script src="{{URL::asset('/js/pages/apexcharts.init.js')}}"></script>

    <script>
        var options = {
            chart: {
                height: 320,
                type: 'pie',
            }, 
            series: [{{$akademiks}}, {{$leaderships}}, {{$karakters}}, {{$kreatifs}}],
            labels: ["Akademik", "Leadership", "Karakter Islami", "Kreativitas & Kewirausahaan"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#eeb902"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#statistik_total"),
            options
        );
        
        chart.render();
    </script>

    
    <script>
        // Donut chart Akademik

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_akademiks_count}}, {{$kom2_akademiks_count}}, {{$kom3_akademiks_count}}, {{$kom4_akademiks_count}}, {{$kom5_akademiks_count}}, {{$kom6_akademiks_count}}, {{$kom7_akademiks_count}}, {{$kom8_akademiks_count}}],
            labels: ["1001", "1002", "1003", "1004", "1005", "1006", "1007", "1008"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#akademik_chart"),
            options
        );
        
        chart.render();
    </script>

    <script>
        // Donut chart Leadership

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_leaderships_count}}, {{$kom2_leaderships_count}}, {{$kom3_leaderships_count}}, {{$kom4_leaderships_count}}, {{$kom5_leaderships_count}}, {{$kom6_leaderships_count}}, {{$kom7_leaderships_count}}, {{$kom8_leaderships_count}}, {{$kom9_leaderships_count}}],
            labels: ["2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2", "#030836"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#leadership_chart"),
            options
        );
        
        chart.render();
    </script>
    <script>
        // Donut chart Karakter Islami

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_karakters_count}}, {{$kom2_karakters_count}}, {{$kom3_karakters_count}}, {{$kom4_karakters_count}}, {{$kom5_karakters_count}}, {{$kom6_karakters_count}}, {{$kom7_karakters_count}}, {{$kom8_karakters_count}}, {{$kom9_karakters_count}}],
            labels: ["3001", "3002", "3003", "3004", "3005", "3006", "3007", "3008", "3009"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2", "#030836"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#karakter_chart"),
            options
        );
        
        chart.render();
    </script>
    <script>
        // Donut chart Kreatifitas

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            }, 
            series: [{{$kom1_kreatifs_count}}, {{$kom2_kreatifs_count}}, {{$kom3_kreatifs_count}}, {{$kom4_kreatifs_count}}, {{$kom5_kreatifs_count}}, {{$kom6_kreatifs_count}}, {{$kom7_kreatifs_count}}, {{$kom8_kreatifs_count}}],
            labels: ["4001", "4002", "4003", "4004", "4005", "4006", "4007", "4008"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc", "#eeb902", "#ecf542", "#42e0f5", "#f542f2"],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]
        
        }
        
        var chart = new ApexCharts(
            document.querySelector("#kreatif_chart"),
            options
        );
        
        chart.render();
    </script>
    @endsection