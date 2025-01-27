<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="BENI Messan">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/assets/images/logo.png') }}">
    <title> {{ env('APP_NAME') }} |  </title>
    
    <link href="{{ asset('template/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <link href="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />

    <link href="{{ asset('template/dist/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css" rel="stylesheet" />

  {{-- <script src="https://cdn.jsdelivr.net/npm/tesseract.js@v4.0.2/dist/tesseract.min.js"></script> 
    <style>
        video, canvas {
            display: block;
            margin: 0 auto;
            border: 1px solid black;
        }
    </style>--}}
</head>



<body>
<div class="main-wrapper">
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
         data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">


        @include('admins.partials.header')
        @include('admins.partials.aside')

        <div class="page-wrapper">

            <div class="container-fluid" style="padding-top: 5px !important;">
                @yield('content')
            </div>

            <footer class="footer text-center text-muted">
                © {{ env('APP_NAME') }} All Rights Reserved. 
            </footer>
        </div>
    </div>
</div>

<script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('template/dist/js/app-style-switcher.js') }}"></script>
<script src="{{ asset('template/dist/js/feather.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('template/dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('template/dist/js/custom.min.js') }}"></script>
<!--This page JavaScript -->
{{--
<script src="{{ asset('template/assets/extra-libs/c3/d3.min.js') }}"></script>
--}}
{{-- <script src="{{ asset('template/assets/libs/chartist/dist/chartist.min.js') }}"></script>
 <script src="{{ asset('template/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
--}}<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
{{--
<script src="{{ asset('template/dist/js/pages/dashboards/dashboard1.min.js') }}"></script>
--}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('backoffice/js/apexcharts/apexcharts.min.js') }}"></script>

<!-- CSS -->
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    var _token = "{{csrf_token()}}";
    var AuthConnect = {!! json_encode(AuthConnect() ?? '') !!};

    function canPermission(permission) {
        if (AuthConnect !== null) {
            var permissions = AuthConnect.permissions;
            if (permissions && permissions.length > 0) {
                for (var i = 0; i < permissions.length; i++) {
                    if (permissions[i].slug === permission) {
                        return true;
                    }
                }
            }
            //   return true;
         return false;
        } else {
           // return true;
            return false;
        }
    }
</script>

<script src="{{ asset('backoffice/js/app_script.js') }}"></script>
<script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('footer-script')

<script>

    $(".preloader ").fadeOut();
</script>
</body>

</html>
