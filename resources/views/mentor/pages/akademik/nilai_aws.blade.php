@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/akademik/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/akademik/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/mentor/penilaian/akademik/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/akademik/dqf">Asrama Putri</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-akademik">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen</th>
                        <th>Waktu</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akademiks as $akademik)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$akademik->nama_warga}}</td>
                        <td>{{$akademik->komponen}}</td>
                        <td>{{$akademik->waktu}}</td>
                        <td>{{$akademik->nama_penilai}}</td>
                        <td>{{$akademik->nilai}}</td>
                        <td>
                            <a href="/mentor/penilaian/akademik/detail/{{$akademik->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                            <a href="/mentor/penilaian/akademik/edit/{{$akademik->id}}" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>                          
                        </td>
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection