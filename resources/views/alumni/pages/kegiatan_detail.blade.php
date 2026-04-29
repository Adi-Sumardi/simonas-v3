@extends('alumni.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Kegiatan</h3>
    </div>
    <div class="section-body">
        <div class="row">
            {{-- <div class="col-12 col-md-12 col-lg-12">
                <a href="/kegiatan/detail/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Kegiatan PDF</a>
            </div> --}}
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Detail Kegiatan</h4></div>
        
        <div class="card-body pt-1">
            <form action="/alumni/kegiatan-update/{{$kegiatan->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kegiatan">Nama Kegiatan:</label>
                                <p>{{$kegiatan->nama_kegiatan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tujuan">Tujuan Kegiatan:</label>
                                <p>{{$kegiatan->tujuan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penyelenggara">Penyelenggara:</label>
                                <p>{{$kegiatan->penyelenggara}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kegiatan">Jenis Kegiatan:</label>
                                <p>{{$kegiatan->jenis_kegiatan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waktu">Waktu Kegiatan:</label>
                                <p>{{$kegiatan->waktu}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat">Tempat Kegiatan:</label>
                                <p>{{$kegiatan->tempat}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Uraian Kegiatan:</label>
                                <p>{{$kegiatan->keterangan}}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Lampiran File:</label>
                                <p><img width="300" src="{{ url ('/data_file/'.$kegiatan->file)}}"></p>
                            </div>
                        </div>
                    </div>                  
                </div>
            </form>
        </div>
</section>
@endsection