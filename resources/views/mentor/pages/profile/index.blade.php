@extends('mentor.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Profile User</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/mentor/profile/edit/{{Auth::user()->id}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Update Data Profile</a>
                {{-- <a href="/mentor/profile/create" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Ganti Foto Profile</a> --}}
                <button type="button" class="btn btn-info icon-left"><i class="fas fa-download"></i> Download Profile</button>
            </div>
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Data User</h4></div>

        <div class="container bootstrap snippet">
            <div class="row">
                  <div class="col-sm-3"><!--left col-->
                      
        
              <div class="text-center">
                <img src="{{ url ('/uploads/avatars/'.Auth::user()->avatar)}}" class="avatar img-circle img-thumbnail" alt="avatar" style="width:125px; height:125px;">
              </div></hr><br>

              <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Aktifitas <i class="fa fa-dashboard fa-1x"></i></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Akademik</strong><span class="badge badge-primary badge-pill">{{ $akademiks}}</span> </li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Leadership</strong><span class="badge badge-primary badge-pill">{{ $leaderships}}</span> </li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Karakter Islami</strong><span class="badge badge-primary badge-pill">{{ $karakters}}</span> </li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Kreatifitas</strong><span class="badge badge-primary badge-pill">{{ $kreatifs}}</span> </li>
              </ul> 
                  
                </div><!--/col-3-->
                <div class="col-sm-9">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home">Data Akun</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#messages">Data Diri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settings">Data Orangtua</a></li>
                    </ul>
        
                      
                  <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <hr>
                          <form class="form" action="##" method="post" id="registrationForm">
                            <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">      
                                    <label for="name"><h4>Nama Akun</h4></label>
                                    <input readonly value="{{Auth::user()->name}}" type="text" class="form-control" name="name" id="name" placeholder="name" title="enter your first name if any.">
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                    <label for="email"><h4>Email</h4></label>
                                    <input readonly value="{{Auth::user()->email}}" type="email" class="form-control" name="email" id="email" placeholder="email" title="enter your last name if any.">
                                </div>
                            </div>
                  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password2"><h4>Role</h4></label>
                                    <input readonly value="{{Auth::user()->role}}" type="text" class="form-control" name="role" id="role" placeholder="role" title="enter your password2.">
                                </div>
                            </div>
                              <div class="form-group">
                                   <div class="col-md-6">
                                        <br>
                                            {{-- <button class="btn btn-lg btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button> --}}
                                            {{-- <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button> --}}
                                    </div>
                              </div>
                            </div>
                          </form>
                      
                      <hr>
                      
                     </div><!--/tab-pane-->
                     <div class="tab-pane" id="messages">
                       
                       <h2></h2>
                       
                       <hr>
                          <form class="form" action="##" method="post" id="registrationForm">
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                  
                                    <label for="name"><h4>Nama Lengkap</h4></label>
                                    <input readonly value="{{Auth::user()->name}}" type="text" class="form-control" name="name" id="name" placeholder="name" title="your name.">
                                </div>
                            </div>
                              
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik"><h4>NIK</h4></label>
                                    <input readonly value="{{Auth::user()->nik}}" type="text" class="form-control" name="nik" id="nik" placeholder="nik" title="your nik.">
                                </div>
                            </div>
                  
                            <div class="col-md-6">
                                <div class="form-group">  
                                    <label for="no_telp"><h4>No. Telepon</h4></label>
                                    <input readonly value="{{Auth::user()->no_telp}}" type="text" class="form-control" name="no_telp" id="no_telp" placeholder="enter phone" title="your phone number.">
                                </div>
                            </div>
                  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_lahir"><h4>Tanggal Lahir</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_lahir}}" type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="your birth date" title="your brith date.">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">      
                                    <label for="alamat"><h4>Alamat</h4></label>
                                    <input readonly value="{{Auth::user()->alamat}}" type="textarea" class="form-control" name="alamat" id="alamat" placeholder="your addres" title="your addres.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">  
                                    <label for="asal_sekolah"><h4>Asal Sekolah</h4></label>
                                    <input readonly value="{{Auth::user()->asal_sekolah}}" type="text" class="form-control" id="asal_sekolah" placeholder="asal sekolah" title="asal sekolah">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">      
                                    <label for="prestasi"><h4>Prestasi</h4></label>
                                    <input readonly value="{{Auth::user()->prestasi}}" type="textarea" class="form-control" name="prestasi" id="prestasi" placeholder="prestasi" title="prestasi kamu.">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">    
                                    <label for="organisasi"><h4>Organisasi</h4></label>
                                    <input readonly value="{{Auth::user()->organisasi}}" type="textarea" class="form-control" name="organisasi" id="organisasi" placeholder="organisasi" title="organisasi kamu.">
                                </div>
                              </div>

                            <div class="col-md-6">
                                <div class="form-group">  
                                    <label for="universitas"><h4>Universitas</h4></label>
                                    <input readonly value="{{Auth::user()->universitas}}" type="text" class="form-control" name="universitas" id="universitas" placeholder="you@email.com" title="enter your email.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fakultas"><h4>Fakultas</h4></label>
                                    <input readonly value="{{Auth::user()->fakultas}}" type="text" class="form-control" id="fakultas" placeholder="fakultas" title="fakultas">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prodi"><h4>Program Studi</h4></label>
                                    <input readonly value="{{Auth::user()->prodi}}" type="text" class="form-control" name="prodi" id="prodi" placeholder="program studi" title="program studi.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">    
                                    <label for="angkatan"><h4>Angkatan</h4></label>
                                    <input readonly value="{{Auth::user()->angkatan}}" type="text" class="form-control" name="angkatan" id="angkatan" placeholder="angkatan" title="angkatan.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_seminar"><h4>Tanggal Seminar</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_seminar}}" type="text" class="form-control" name="tgl_seminar" id="tgl_seminar" placeholder="tanggal seminar skripsi" title="tanggal seminar skripsi.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">    
                                    <label for="tgl_skripsi"><h4>Tanggal Skripsi</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_skripsi}}" type="text" class="form-control" id="tgl_skripsi" placeholder="tanggal sidang skripsi" title="tanggal sidang skripsi">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">    
                                    <label for="tgl_wisuda"><h4>Tanggal Wisuda</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_wisuda}}" type="text" class="form-control" name="tgl_wisuda" id="tgl_wisuda" placeholder="tanggal wisuda" title="tanggal wisuda.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">    
                                    <label for="asrama"><h4>Asrama</h4></label>
                                    <input readonly value="{{Auth::user()->asrama}}" type="text" class="form-control" name="asrama" id="asrama" placeholder="asrama" title="asrama.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_masuk"><h4>Tanggal Masuk Asrama</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_masuk}}" type="text" class="form-control" name="tgl_masuk" id="tgl_masuk" placeholder="tanggal masuk asrama" title="tanggal masuk asrama.">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_keluar"><h4>Tanggal Keluar Asrama</h4></label>
                                    <input readonly value="{{Auth::user()->tgl_keluar}}" type="text" class="form-control" name="tgl_keluar" id="tgl_keluar" placeholder="tanggal keluar asrama" title="tanggal keluar asrama.">
                                </div>
                            </div>

                            <div class="form-group">
                                   <div class="col-xs-12">
                                        <br>
                                          {{-- <button class="btn btn-lg btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button> --}}
                                           {{-- <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button> --}}
                                    </div>
                              </div>
                            </div>
                          </form>
                       
                     </div><!--/tab-pane-->
                     <div class="tab-pane" id="settings">
                            
                           
                          <hr>
                          <form class="form" action="##" method="post" id="registrationForm">
                            <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group"> 
                                      <label for="nama_ayah"><h4>Nama Ayah</h4></label>
                                      <input readonly value="{{Auth::user()->nama_ayah}}" type="text" class="form-control" name="nama_ayah" id="nama ayah" placeholder="nama ayah kandung" title="nama ayah kandung.">
                                  </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ibu"><h4>Nama Ibu</h4></label>
                                    <input readonly value="{{Auth::user()->nama_ibu}}" type="text" class="form-control" name="nama_ibu" id="nama_ibu" placeholder="nama ibu kandung" title="nama ibu kandung.">
                                </div>
                              </div>
                  
                              <div class="form-group">
                                   <div class="col-xs-12">
                                        <br>
                                          {{-- <button class="btn btn-lg btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button> --}}
                                           <!--<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>-->
                                    </div>
                              </div>
                            </div>
                          </form>
                      </div>
                       
                      </div><!--/tab-pane-->
                  </div><!--/tab-content-->
        
                    </div><!--/col-9-->
                    <div class="card-footer"><h4></h4></div>
                </div>
            </div>
            
    </div>
</section>
@endsection