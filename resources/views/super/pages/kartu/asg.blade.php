@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kartu Hasil Penilaian Warga Asrama</h3>
    </div>
    <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs shadow-sm p-3 mb-5 bg-white rounded">
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/asgj">ASGJ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/kartu/asg">ASG</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/aws">AWS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/dqf">Asrama Putri</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="col-12 col-md-12 col-lg-12" style="overflow: scroll;">

            <div class="container">
                <div class="row mb-3">
                    @foreach ($users as $user)
                    <div class="col-md-6">
                        <div class="d-flex flex-row border rounded">
                              <div class="card-image p-0 w-25">
                                  <img src="{{ url ('/uploads/avatars/'.$user->avatar)}}" class="img-thumbnail border-0" />
                                  
                              </div>
                              <div class="pl-3 pt-2 pr-2 pb-2 w-75 border-left">
                                      <h4 class="text-primary">{{$user->name}}</h4>
                                      <h5 class="text-info">{{$user->asrama}}</h5>
                                      <ul class="m-0 float-left" style="list-style: none; margin:0; padding: 0">
                                          <li><i class="card-universitas far fa-building"></i>{{$user->universitas}}</li>
                                      </ul>
                                    <p class="text-right m-0"><a href="/kartu/detail/{{$user->id}}" class="btn btn-primary"><i class="far fa-user"></i> View Detail</a></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>

                {{ $users->links() }}
            
            </div>
            
        </div> 
    </div>
</section>
@endsection