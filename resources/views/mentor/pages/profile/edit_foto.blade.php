@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Ganti Foto Profile</h3>
    </div>
    <div class="card card-primary">

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <img src="/uploads/avatars/{{ Auth::user()->avatar}}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px">
                            <h2>{{Auth::user()->name}}'s Profile</h2>
                            <form action="/mentor/profile/foto/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <label>Update Profile Image</label><br>
                                <input type="file" name="avatar">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="right btn btn-sm btn-primary">
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection