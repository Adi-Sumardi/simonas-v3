@extends('mahasiswa.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa/warga/asgj">ASGJ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa/warga/asg">ASG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa/warga/aws">AWS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mahasiswa/warga/dqf">Asrama Putri</a>
                </li>
            </ul>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-warga">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Lengkap</th>
                        <th>Foto</th>
                        <th>Asrama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$user->name}}</td>
                        <td><img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="avatar img-circle" style="width:50px; height:50px;"></td>
                        <td>{{$user->asrama}}</td>
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection