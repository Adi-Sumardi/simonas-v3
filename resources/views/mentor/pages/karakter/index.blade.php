@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Karakter Islami Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/mentor/karakter/create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
                <a href="/mentor/karakter/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Akademik PDF</a>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">    
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-karakter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($karakters as $index => $karakter)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$karakter->nama_warga}}</td>
                        <td>{{$karakter->kegiatan}}</td>
                        <td>{{$karakter->waktu}}</td>
                        <td>{{$karakter->tempat}}</td>
                        <td>{{$karakter->keterangan}}</td>
                        <td>                            
                            <form action="/mentor/karakter/delete/{{$karakter->id}}" method="POST">
                                <a href="/mentor/karakter/detail/{{$karakter->id}}" class="btn btn-icon btn-secondary btn-action" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                    <a href="/mentor/karakter/edit/{{$karakter->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
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