@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Periode Penilaian Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/periode/create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
           
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-periode">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($users as $user)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$user->name}}</td>
                        <td><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="avatar img-circle" style="width:50px; height:50px;"></td>
                        <td>{{$user->asrama}}</td>
                        <td>                            
                            <a href="/warga-detail/{{$user->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                        </td>
                    </tr>       
                    @endforeach --}}
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection