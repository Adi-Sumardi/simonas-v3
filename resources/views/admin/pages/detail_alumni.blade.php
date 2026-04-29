@extends('admin.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Alumni Asrama</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Detail Data Alumni Asrama</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/admin/alumni/detail/{{$alumni->id}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label>
                                <p>{{$alumni->nama_lengkap}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label>
                                <p>{{$alumni->email}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telp">Nomor Telphone:</label>
                                <p>{{$alumni->no_telp}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama">Asrama:</label>
                                <p>{{$alumni->asrama}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="angkatan">Angkatan:</label>
                                <p>{{$alumni->angkatan}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gelar">Gelar:</label>
                                <p>{{$alumni->gelar}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_asal">Alamat Asal:</label>
                                <p>{{$alumni->alamat_asal}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat_sekarang">Alamat Sekarang:</label>
                                <p>{{$alumni->alamat_sekarang}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan:</label>
                                <p>{{$alumni->pekerjaan}}</p>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</section>
@endsection