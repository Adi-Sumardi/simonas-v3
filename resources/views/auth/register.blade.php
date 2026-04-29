@extends('layouts.master-without-nav')

@section('title') Register @endsection

@section('body')

<body>
    @endsection

    @section('content')

    <div class="account-pages my-5 pt-sm-5" style="background-size: cover; background-image: url({{asset('images/bg-effect.png')}})">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-login text-center">
                            <div class="bg-login-overlay"></div>
                            <div class="position-relative">
                                <h5 class="text-white font-size-20"> {{ __('Register') }} SIMONAS</h5>
                                <p class="text-white-50 mb-0">Get your Account SIMONAS now</p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="/images/simonas_logo.png" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">

                            <div class="p-2">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="useremail">{{ __('E-Mail') }}</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="useremail" placeholder="Enter email" autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="name">{{ __('Nama') }}</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="Enter name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="no_telp">{{ __('Nomor Telepon') }}</label>
                                        <input type="number" name="no_telp" value="{{ old('no_telp') }}" required class="form-control @error('no_telp') is-invalid @enderror" autofocus placeholder="Enter Phone Number">
                                        @error('no_telp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="asrama">{{ __('Asrama') }}</label>
                                        <select name="asrama" class="form-control @error('asrama') is-invalid @enderror" required>
                                            <option value="">-Pilih Asrama-</option>
                                            <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                            <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                            <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                            <option value="Asrama Putri">Asrama Putri</option>
                                        </select>
                                        @error('asrama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tgl_masuk">{{ __('Tanggal Masuk Asrama') }}</label>
                                        <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk') }}" required class="form-control @error('tgl_masuk') is-invalid @enderror" autofocus placeholder="Register Date Dormitory">
                                        @error('tgl_masuk')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="role">{{ __('Role') }}</label>
                                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                            <option value="">-Pilih Role-</option>
                                            {{--  <option value="super">Super Admin</option>
                                            <option value="admin">Admin/Direktur</option>  --}}
                                            <option value="mentor">Mentor Warga</option>
                                            <option value="mahasiswa">Warga Asrama</option>
                                            <option value="alumni">Alumni Asrama</option>
                                        </select>
                                        @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">{{ __('Password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" id="userpassword" placeholder="Enter password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">{{ __('Confirm Password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="userconfirmpassword" placeholder="Confirm password">
                                    </div>

                                    <div class="form-group">
                                        <div class="captcha">
                                            <span>{!! captcha_img('flat') !!}</span>
                                            <button type="button" class="btn btn-danger reload" id="reload" >
                                                <i class="mdi mdi-reload"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="captcha" class="form-control @error('captcha') is-invalid @enderror" id="captcha" placeholder="Enter Captcha">
                                        @error('captcha')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" id="register" type="submit"> {{ __('Register') }}</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">By registering you agree to the Skote <a href="#" class="text-primary">Terms of Use</a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Already have an account ? <a href="login" class="font-weight-medium text-primary"> Login</a> </p>
                        <p>© <script> document.write(new Date().getFullYear()) 
                            </script> SIMONAS App. Crafted with <i class="mdi mdi-heart text-danger"></i> by TIM IT YAPI</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ URL::asset('libs/jquery/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('libs/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{ URL::asset('libs/metismenu/metismenu.min.js')}}"></script>
    <script src="{{ URL::asset('libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ URL::asset('libs/node-waves/node-waves.min.js')}}"></script>

    <script src="{{ URL::asset('js/app.min.js')}}"></script>

    <script>
        $('#reload').click(function(){
            $.ajax({
                type:'Get',
                url:'reload-captcha',
                success:function(data){
                    $(".captcha span").html(data.captcha)
                }
            });
        });
    </script>
    
@endsection