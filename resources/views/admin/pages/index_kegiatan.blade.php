@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/admin/kegiatan-create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
                <a href="/admin/kegiatan/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Kegiatan PDF</a>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
    
            <div class="row">
                <table class="table table-bordered table-striped table-hover" id="data-kegiatan">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kegiatan</th>
                            <th>Penyelenggara</th>
                            <th>Waktu</th>
                            <th>Tempat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatans as $kegiatan)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$kegiatan->nama_kegiatan}}</td>
                            <td>{{$kegiatan->penyelenggara}}</td>
                            <td>{{$kegiatan->waktu}}</td>
                            <td>{{$kegiatan->tempat}}</td>
                            <td>                           
                                <form action="/admin/kegiatan-delete/{{$kegiatan->id}}" method="POST">
                                    <a href="/admin/kegiatan-detail/{{$kegiatan->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                    <a href="/admin/kegiatan-edit/{{$kegiatan->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>       
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection