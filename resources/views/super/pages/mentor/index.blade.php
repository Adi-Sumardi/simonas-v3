@extends('super.layouts.master')

@section('title')
    List Mentor
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('super.common-components.breadcrumb')
        @slot('title')
        List Mentor
        @endslot
        @slot('title_li')
        List Mentor
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($users as $index => $user)
                        <div class="col-lg-6">
                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-md-2">
                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="{{ url('/data_photo/' . $user->avatar) }}" alt="Card image" height="40" width="40">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <a href="/super-mentor-asrama-detail/{{ $user->id }}" class="card-title">{{$user->name}}</a>
                                            <br>
                                            <h6 class="card-text">{{$user->asrama}}</h6>
                                            <p class="card-text"><small class="text-muted">{{$user->status_warga}}</small></p>
                                            @if ($user->average_ip !== null)
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: {{ number_format($user->average_ip, 2) }}
                                                    </small>
                                                </p>
                                            @else
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        IPK: Data tidak tersedia
                                                    </small>
                                                </p>
                                            @endif
                                            {{--  <p class="card-text"><small class="text-muted">IPK: {{$user->average_ip}}</small></p>  --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    Jumlah Data: {{$users->total()}}
                    <br/>
                    <br/>
                    {{$users->links()}}
                    <!-- end row -->

                    {{--  <h4 class="card-title">List Warga Asrama</h4>
                    <div style="overflow: scroll">
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Asrama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{$index + $users->firstItem()}}</td>
                                        <td><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="avatar img-circle" style="width:auto; height:50px;"></td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->asrama}}</td>
                                        <td>
                                            <a href="/super-warga-asrama-detail/{{ $user->id }}"
                                                class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail"><i
                                                    class="far fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        Jumlah Data: {{$users->total()}}
                        <br/>
                        <br/>
                        {{$users->links()}}
                    </div>  --}}
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <!-- plugin js -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js') }}"></script>

    <!-- Calendar init -->
    <script src="{{ URL::asset('js/pages/dashboard.init.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('/js/pages/sweet-alerts.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('/js/pages/datatables.init.js') }}"></script>
@endsection
