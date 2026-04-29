@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/kegiatan-create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
                <a href="/kegiatan/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Kegiatan PDF</a>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="/kegiatan/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/dqf">Asrama Putri</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/asrama">Direktorat Keasramaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/yapi">YAPI</a>
                    </li> --}}
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
    
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatans as $index => $kegiatan)
                    <tr>
                        {{-- <td>{{$loop->iteration}}</td> --}}
                        <td>{{ $index + $kegiatans->firstItem() }}</td>
                        <td>{{$kegiatan->nama_kegiatan}}</td>
                        <td>{{$kegiatan->jenis_kegiatan}}</td>
                        <td>{{$kegiatan->waktu}}</td>
                        <td>{{$kegiatan->tempat}}</td>
                        <td>                           
                            <form action="/kegiatan-delete/{{$kegiatan->id}}" method="POST">
                                <a href="/kegiatan-detail/{{$kegiatan->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/kegiatan-edit/{{$kegiatan->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>       
                    @endforeach
                </tbody>
            </table>
            Jumlah Data: {{$kegiatans->total()}}<br/>

            {{$kegiatans->links()}}
        </div>
    </div>
</section>
@endsection