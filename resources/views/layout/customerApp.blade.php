<!DOCTYPE html>
<html dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="BENI Messan">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template/assets/images/logo.png') }}">
        <title> {{ env('APP_NAME') }} |  </title>

        {{-- Les vues/JS front (fa fa-eye, fa fa-edit, fas fa-plus...) utilisent
             Font Awesome, jamais chargé dans ce layout (contrairement à
             LandingPage/HomePage) : sans lui ces icônes sont invisibles,
             ancien design comme nouveau. --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        {{-- .sidebar-brand (customers.partials.aside) est un bloc ajouté pour
             espace-client-v2.css : masqué par défaut ici pour ne pas polluer
             l'ancien design (style.css ne le connaît pas), réaffiché par
             espace-client-v2.css lui-même quand ce fichier est chargé. --}}
        <style>.sidebar-brand { display: none; }</style>

        {{-- <link href="{{ asset('template/dist/css/style.css') }}" rel="stylesheet"> --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css" rel="stylesheet" />

        {{-- Espace Client V2 — en construction (voir en-tête du fichier).
             Chargé après style.css : surcharge progressivement les mêmes
             classes, section par section, jusqu'à bascule exclusive finale. --}}
        <link href="{{ asset('css/v2/espace-client-v2.css') }}?v={{ filemtime(public_path('css/v2/espace-client-v2.css')) }}" rel="stylesheet">
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


                @include('customers.partials.header')
                @include('customers.partials.aside')

                <div class="page-wrapper">

                    <div class="container-fluid page-content" id="page-content">
                        @yield('content')
                    </div>

                    <footer class="footer text-center text-muted">
                        © {{ env('APP_NAME') }} All Rights Reserved
                    </footer>
                </div>
            </div>
        </div>
        @include('layout.partial.customer_js_script')
    </body>

</html>
