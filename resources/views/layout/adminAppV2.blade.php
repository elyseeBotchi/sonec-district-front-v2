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
    <title>@yield('title', env('APP_NAME')) | </title>

    {{-- Scripts/CSS métier inchangés (mêmes libs que layout.adminApp, requis par
         admins.partials.aside / admins.partials.header et les scripts *.js
         (ex: statistique_detaille.js) partagés avec les pages existantes) --}}
    <link href="{{ asset('template/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link href="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('template/dist/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    
    <link href="{{ asset('css/v2/district-v2.css') }}" rel="stylesheet">

    @stack('head')
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div id="main-wrapper" class="v2-shell" data-theme="light" data-layout="vertical" data-navbarbg="skin6"
            data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

            @include('admins.partials.header')

            <div class="sidebar-brand">
                <span class="sidebar-brand__logo">
                    <img src="{{ asset('template/assets/images/logo.png') }}" alt="{{ env('APP_NAME') }}"
                        style="height: 22px; width: auto;">
                </span>
                <span class="sidebar-brand__name">{{ env('APP_NAME') }}</span>
            </div>

            @include('admins.partials.aside')

            <div class="page-wrapper">
                <div class="v2-content">
                    @yield('content')
                </div>

                <footer class="footer text-center text-muted">
                    © {{ env('APP_NAME') }} All Rights Reserved.
                </footer>
            </div>

            {{-- Bottom nav mobile : raccourcis + accès au menu complet existant
                 (le vrai menu, avec toutes les permissions, reste dans
                 admins.partials.aside, ouvert ici en off-canvas). --}}
            <nav class="v2-bottom-nav">
                <a href="{{ route('panel.home') }}" class="v2-bottom-nav__item {{ request()->routeIs('panel.home') ? 'is-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Accueil
                </a>
                <a href="javascript:void(0)" data-v2-toggle-sidebar class="v2-bottom-nav__item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    Menu
                </a>
                <a href="{{ route('panel.securite.compte') }}" class="v2-bottom-nav__item {{ request()->routeIs('panel.securite.*') ? 'is-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profil
                </a>
            </nav>
        </div>
    </div>

    <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('template/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('template/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('template/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('template/dist/js/custom.min.js') }}"></script>

    <script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('backoffice/js/apexcharts/apexcharts.min.js') }}"></script>

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
                return false;
            } else {
                return false;
            }
        }
    </script>

    <script src="{{ asset('backoffice/js/app_script.js') }}"></script>
    <script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Comportement propre au socle V2 (backdrop mobile, etc.) --}}
    <script src="{{ asset('js/v2/district-v2.js') }}"></script>

    @stack('footer-script')

    <script>
        $(".preloader ").fadeOut();
    </script>
</body>

</html>
