@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Karakter Islami Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/admin/penilaian/karakter/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Penilaian Karakter Islami PDF</a>
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
                        <th>Kegiatan</th>
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
                        <td>{{$karakter->kegiatan}}</td>
                        <td>{{$karakter->waktu}}</td>
                        <td>{{$karakter->nama_penilai}}</td>
                        <td>{{$karakter->nilai}}</td>
                        <td>                            
                                <a href="/admin/penilaian/karakter/detail/{{$karakter->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/admin/penilaian/karakter/edit/{{$karakter->id}}" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>
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