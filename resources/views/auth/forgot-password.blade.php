@extends('layouts.master-without-nav')

@section('title') Lupa Password @endsection

@section('body')

<body>
    @endsection

    @section('content')

    <div class="home-btn d-none d-sm-block">
        {{--  <a href="index" class="text-dark"><i class="fas fa-home h2"></i></a>  --}}
    </div>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-login text-center">
                            <div class="bg-login-overlay"></div>
                            <div class="position-relative">
                                <h5 class="text-white font-size-20">Lupa Password ya?</h5>
                                <p class="text-white-50 mb-0">{{ __('Reset Password') }}</p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="{{ asset('../images/simonas_logo.png')}}" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            @if(session()->has('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Sukses!</strong> Silahkan cek email Anda untuk tautan reset kata sandi.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="p-2">
                            <form method="POST" action="{{ route('email.password') }}">
                                @csrf
                                    <div class="form-group">
                                        <label for="useremail">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit"> {{ __('Send Password Reset Link') }}</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">By registering you agree to the Skote <a href="#" class="text-primary">Terms of Use</a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Already have an account ? <a href="/login" class="font-weight-medium text-primary"> Login</a> </p>
                        <p>© <script> document.write(new Date().getFullYear()) </script> Qovex. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
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

    @endsection