@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">History Pengisian Akademik Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-akademik">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen Pengaderan</th>
                        <th>Waktu Pengisian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akademiks as $akademik)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$akademik->nama_warga}}</td>
                        <td>{{$akademik->komponen}}</td>
                        <td>{{$akademik->waktu}}</td>
                        {{-- <td>                            
                            <a href="/penilaian/akademik/detail/{{$akademik->id}}" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                            <a href="/penilaian/akademik/edit/{{$akademik->id}}" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                        </td> --}}
                    </tr>       
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
</section>
@endsection