<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title') SIMONAS-Apps</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
   
    <!-- Bootstrap 4.1.1 -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    
    <!-- Ionicons -->
    <link href="//fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Datatables -->
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>

@yield('page_css')
<!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css')}}">
    @yield('page_css')


    @yield('css')
</head>
<body>
    @section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"></h3>
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <div class="hero bg-primary text-white">
                  <div class="hero-inner">
                    <h2>Selamat Datang, {{\Illuminate\Support\Facades\Auth::user()->name}}</h2>
                    <p class="lead">Ini adalah dashboard ADMIN yang digunakan oleh Pengurus YAPI, Direktur Keasramaan, dan Direktur Asrama.</p>
                  </div>
                </div>
              </div>
        </div>
    </section>
    @endsection
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @include('admin.header')

        </nav>
        <div class="main-sidebar main-sidebar-postion">
            @include('admin.sidebar')
        </div>
        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
        <footer class="main-footer">
            @include('admin.footer')
        </footer>
    </div>
</div>

@include('profile.change_password')

</body>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>
<script src="{{ mix('assets/js/profile.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>

<!-- Datatables JS File -->
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>

<!-- Chart JS File -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


@yield('page_js')
@yield('scripts')
<script>
    let loggedInUser =@json(\Illuminate\Support\Facades\Auth::user());
    let loginUrl = '{{ route('login') }}';
    // Loading button plugin (removed from BS4)
    (function ($) {
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
</script>

<script>
    $(document).ready(function() {
    $('#data-kegiatan').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-asrama').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-warga').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-alumni').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-akademik').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-leadership').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-karakter').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-kreatif').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-akses').DataTable();
} );
</script>

<script>
    $(document).ready(function() {
    $('#data-form').DataTable();
} );
</script>

<script>
    // === include 'setup' then 'config' above ===
  
    var myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
</script>

</html>
