@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Leadership Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                {{-- <a href="/admin/leadership/create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a> --}}
                <a href="/admin/leadership/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Leadership PDF</a>
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
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Keterangan</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaderships as $leadership)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$leadership->nama_warga}}</td>
                        <td>{{$leadership->kegiatan}}</td>
                        <td>{{$leadership->waktu}}</td>
                        <td>{{$leadership->tempat}}</td>
                        <td>{{$leadership->keterangan}}</td>
                        {{-- <td>                            
                                <a href="/akademik/detail/{{$akademik->id}}" class="btn btn-icon btn-secondary btn-action" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <form action="/admin/leadership/delete/{{$leadership->id}}" method="POST">
                                    <a href="/admin/leadership/edit/{{$leadership->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                        </td> --}}
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection