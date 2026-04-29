@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/admin/penilaian/akademik/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Penilaian Akademik PDF</a>
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
                        <th>Kegiatan</th>
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
                        <td>{{$akademik->kegiatan}}</td>
                        <td>{{$akademik->waktu}}</td>
                        <td>{{$akademik->nama_penilai}}</td>
                        <td>{{$akademik->nilai}}</td>
                        <td>
                            <a href="/admin/penilaian/akademik/detail/{{$akademik->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                            <a href="/admin/penilaian/akademik/edit/{{$akademik->id}}" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>                          
                        </td>
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection