@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detail Kegiatan Kreatifitas dan Wirausaha</h3>
    </div>
    <div class="section-body">
        <div class="row">
        </div>
    </div>
    <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
        <div class="card-header"><h4>Detail Kegiatan Kreatifitas dan Wirausaha</h4></div>
        
        <div class="card-body pt-1">
            <form action="/mentor/kreatif-update/{{$kreatif->id}}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_warga">Nama Warga:</label>
                                <p>{{$kreatif->nama_warga}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="komponen">Komponen Kurikulum Kreativitas dan Kewirausahaan:</label>
                                <p>{{$kreatif->komponen}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kegiatan">Nama Kegiatan:</label>
                                <p>{{$kreatif->kegiatan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waktu">Waktu Kegiatan:</label>
                                <p>{{$kreatif->waktu}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat">Tempat Kegiatan:</label>
                                <p>{{$kreatif->tempat}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Uraian Kegiatan:</label>
                                <p>{{$kreatif->keterangan}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat" class="control-label">Nama Penilai:</label><span class="text-danger">*</span>
                                <p value="{{$kreatif->nama_penilai}}" type="text" name="nama_penilai"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="control-label">Nilai:</label><span class="text-danger">*</span>
                                <p value="{{$kreatif->nilai}}" type="text" name="nilai">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan">Lampiran File:</label>
                                <p><iframe src="{{url('storage/'.$kreatif->file)}}" frameborder="0" style="width: 300px; height: 200px; text-align: center;"></iframe></p>
                            </div>
                        </div>
                    </div>                  
                </div>
            </form>
        </div>
</section>
@endsection