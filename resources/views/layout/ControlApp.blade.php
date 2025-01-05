<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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
</head>



<body data-instant-allow-query-string data-instant-allow-external-links>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

            {{-- ######################################## --}}
            <header class="topbar" data-navbarbg="skin6">
                <nav class="navbar top-navbar navbar-expand-md">
                    <div class="navbar-header" data-logobg="skin6">
                        <!-- This is for the sidebar toggle which is visible on mobile only -->
                        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                                class="ti-menu ti-close"></i></a>
                        <!-- ============================================================== -->
                        <!-- Logo -->
                        <!-- ============================================================== -->
                        <div class="navbar-brand">
                            <!-- Logo icon -->
                            <a href="">
                                <b class="logo-icon">
                                    <img src="{{ asset('template/assets/images/logo.png') }}"  width="50px" alt="homepage" class="dark-logo" />
                                </b>
                            </a>
                        </div>
                        <!-- ============================================================== -->
                        <!-- End Logo -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Toggle which is visible on mobile only -->
                        <!-- ============================================================== -->
                        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                                class="ti-more"></i></a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <!-- ============================================================== -->
                        <!-- toggle and nav items -->
                        <!-- ============================================================== -->
                        <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        
                        </ul>
                        <!-- ============================================================== -->
                        <!-- Right side toggle and nav items -->
                        <!-- ============================================================== -->
                        <ul class="navbar-nav float-right">
                            <!-- ============================================================== -->
        
                            <!-- User profile and search -->
                            <!-- ============================================================== -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                    <img
                                        @isset(AuthConnect()['avatar'])
                                            src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.AuthConnect()['uuid'].'/'.AuthConnect()['avatar']) }}"
                                        @else
                                            @isset(AuthConnect()['civility'])
                                                src="{{ asset(AuthConnect()['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                                            @else
                                                src="backoffice/man.png"
                                            @endisset                                     
                                        @endisset
                                        alt="user" class="rounded-circle"
                                        width="40">
                                    <span class="ml-2 d-none d-lg-inline-block">
                                        <span>Bienvenu,</span> <span
                                            class="text-dark">{{ AuthConnect()['firstname'] ?? '' }}</span>
                                        <i data-feather="chevron-down" class="svg-icon"></i>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                                        Mon profile
                                    </a>
                                
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i data-feather="credit-card" class="svg-icon mr-2 ml-1"></i>
                                        Mes redevances 
                                    </a>
            
                                    @isset($lock)
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i data-feather="mail" class="svg-icon mr-2 ml-1"></i>
                                            Inbox
                                        </a>
                                    @endisset
            
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i data-feather="settings" class="svg-icon mr-2 ml-1"></i>
                                        parametre du compte
                                    </a>
                            
                                    <div class="dropdown-divider"></div>
                                
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                                        <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                        Déconnexion
            
                                        <form id="logout-form" action="{{ route('panel.logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </a>
            
                                </div>
                            </li>
                            <!-- ============================================================== -->
                            <!-- User profile and search -->
                            <!-- ============================================================== -->
                        </ul>
                    </div>
                </nav>
            </header>
            
        {{-- ##################################### --}}
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
<script src="{{ asset('template/assets/libs/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
{{--
<script src="{{ asset('template/dist/js/pages/dashboards/dashboard1.min.js') }}"></script>
--}}

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
            return true;
           // return false;
        } else {
            return true;
           // return false;
        }
    }
</script>

<script src="{{ asset('backoffice/js/app_script.js') }}"></script>
<script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

@stack('footer-script')

<script>

    $(".preloader ").fadeOut();
</script>
</body>

</html>
