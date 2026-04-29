@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Asrama YAPI</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/admin/asrama/create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-asrama">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Asrama</th>
                        <th>Tahun Jabatan</th>
                        <th>Nama Direktur</th>
                        <th>Nama Ketua</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asramas as $asrama)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$asrama->nama_asrama}}</td>
                        <td>{{$asrama->tahun_jabatan}}</td>
                        <td>{{$asrama->direktur}}</td>
                        <td>{{$asrama->ketua}}</td>
                        <td>                            
                            <form action="/admin/asrama/delete/{{$asrama->id}}" method="POST">
                                <a href="/admin/asrama/edit/{{$asrama->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
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
</section>
@endsection