@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Karakter Islami Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/karakter/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/karakter/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/mentor/penilaian/karakter/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/penilaian/karakter/dqf">Asrama Putri</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card card-primary col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">    
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-karakter">
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
                    @foreach($karakters as $index => $karakter)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$karakter->nama_warga}}</td>
                        <td>{{$karakter->komponen}}</td>
                        <td>{{$karakter->waktu}}</td>
                        <td>{{$karakter->nama_penilai}}</td>
                        <td>{{$karakter->nilai}}</td>
                        <td>                            
                                <a href="/mentor/penilaian/karakter/detail/{{$karakter->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/mentor/penilaian/karakter/edit/{{$karakter->id}}" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>
                                {{-- <form action="/akademik/delete/{{$akademik->id}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form> --}}
                        </td>
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection