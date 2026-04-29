@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Leadership Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/leadership/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Penilaian Leadership PDF</a>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/leadership/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/leadership/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/leadership/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/leadership/dqf">Asrama Putri</a>
                    </li>
                </ul> 
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-leadership">
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
                    @foreach($leaderships as $leadership)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$leadership->nama_warga}}</td>
                        <td>{{$leadership->komponen}}</td>
                        <td>{{$leadership->waktu}}</td>
                        <td>{{$leadership->nama_penilai}}</td>
                        <td>{{$leadership->nilai}}</td>
                        <td>                            
                                <a href="/penilaian/leadership/detail/{{$leadership->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/penilaian/leadership/edit/{{$leadership->id}}" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>
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