@extends('layouts.master-without-nav')

@section('title') Success @endsection

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
                                <h5 class="text-white font-size-20">Password Sudah Sukes Dirubah Silahkan Login! !</h5>
                                <p class="text-white-50 mb-0">{{ __('Reset Password') }}</p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="{{ asset('../images/simonas_logo.png')}}" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="p-2">
                                <p>Already have an account ? <a href="/login" class="font-weight-medium text-primary"> Login</a> </p>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
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