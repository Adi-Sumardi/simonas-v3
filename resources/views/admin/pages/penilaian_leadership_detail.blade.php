@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Kegiatan Leadership</h3>
    </div>
    <div class="section-body">
        <div class="row">
        </div>
    </div>
    <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
        <div class="card-header"><h4>Detail Kegiatan Leadership</h4></div>
        
        <div class="card-body pt-1">
            <form action="/admin/penilaian/leadership/update/{{$leadership->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_warga">Nama Warga:</label>
                            <p>{{$leadership->nama_warga}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="komponen">Komponen Kurikulum Leadership:</label>
                            <p>{{$leadership->komponen}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kegiatan">Nama Kegiatan:</label>
                            <p>{{$leadership->kegiatan}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu">Waktu Kegiatan:</label>
                            <p>{{$leadership->waktu}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat">Tempat Kegiatan:</label>
                            <p>{{$leadership->tempat}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan">Uraian Kegiatan:</label>
                            <p>{{$leadership->keterangan}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tempat" class="control-label">Nama Penilai:</label><span class="text-danger">*</span>
                            <p>{{$leadership->nama_penilai}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="control-label">Nilai:</label><span class="text-danger">*</span>
                            <p>{{$leadership->nilai}}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="keterangan">Lampiran File:</label>
                            <p><iframe src="{{url('storage/'.$leadership->file)}}" frameborder="0" style="width: 300px; height: 200px; text-align: center;"></iframe></p>
                            <a href="/admin/leadership/file/download/{{$leadership->file}}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download File</a>
                        </div>
                    </div>
                </div>                  
            </div>
            </form>
        </div>
</section>
@endsection