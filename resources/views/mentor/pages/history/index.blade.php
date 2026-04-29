@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">History Penilaian Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                    <a class="nav-link active" href="/mentor/history/akademik">Akademik</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/leadership">Leadership</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/karakter">Karakter</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/kreatif">Kreatif</a>
                    </li>
                </ul>
            </div>
            {{-- <div class="col-12 col-md-12 col-lg-12">
                <a href="/mentor/history/akademik" class="btn btn-info"> History Akademik</a>
                <a href="/mentor/history/leadership" class="btn btn-info"> History Leadership</a>
                <a href="/mentor/history/karakter" class="btn btn-info"> History Karakter Islam</a>
                <a href="/mentor/history/kreatif" class="btn btn-info"> History Kreatifitas dan Wirausaha</a>
            </div> --}}
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-akademik">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen Pengaderan</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akademiks as $akademik)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$akademik->nama_warga}}</td>
                        <td>{{$akademik->komponen}}</td>
                        <td>{{$akademik->nama_penilai}}</td>
                        <td>{{$akademik->nilai}}</td>
                        {{-- <td>                            
                            <a href="/penilaian/akademik/detail/{{$akademik->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                            <a href="/penilaian/akademik/edit/{{$akademik->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                        </td> --}}
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection