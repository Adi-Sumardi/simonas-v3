@extends('layouts.master-without-nav')

@section('title') Login @endsection

@section('body')

<body>
    @endsection

    @section('content')

    @if(session()->has('status'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Berhasil Reset Password, Silahkan Login!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="account-pages my-5 pt-sm-5" style="background-size: cover; background-image: url({{asset('images/bg-effect.png')}})">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-login text-center">
                            <div class="bg-login-overlay"></div>
                            <div class="position-relative">
                                <h5 class="text-white font-size-20">Welcome Back !</h5>
                                <p class="text-white-50 mb-0">Sign in to continue to SIMONAS App.</p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="images/simonas_logo.png" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="p-2">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" @if(old('email')) value="{{ old('email') }}" @endif required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="captcha">
                                            <span>{!! captcha_img('math') !!}</span>
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

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="customControlInline" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customControlInline">{{ __('Remember Me') }}</label>
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" id="login" type="submit">{{ __('Login') }}</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="{{ route('lupa.password') }}" class="text-muted"><i class="mdi mdi-lock mr-1"></i> {{ __('Forgot Your Password?') }}</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Belum punya account ? <a href="register" class="font-weight-medium text-primary"> Signup now </a> </p>
                        <p>© <script>
                                document.write(new Date().getFullYear())
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
                    url:'reload-captcha-login',
                    success:function(data){
                        $(".captcha span").html(data.captcha)
                    }
                });
            });
        </script>
    @endsection