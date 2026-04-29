@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">List Request Akun User</h3>
    </div>
    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="row">
                <a href="/form/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Request Akun PDF</a>
            </div>
        </div>
        <div class="card card-primary" style="overflow: scroll;">
            
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                </div>
                <table class="table table-bordered table-striped table-hover" id="data-form">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor Whatsapp</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$form->name}}</td>
                            <td>{{$form->email}}</td>
                            <td>{{$form->no_wa}}</td>
                            <td>{{$form->status}}</td>
                            <td>                            
                                <form action="/form/delete/{{$form->id}}" method="POST">
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
    </div>
</section>
@endsection