
<script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('template/dist/js/app-style-switcher.js') }}"></script>
<script src="{{ asset('template/dist/js/feather.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('template/dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('template/dist/js/custom.min.js') }}"></script>

<script src="{{ asset('template/assets/libs/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('template/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('template/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    var _token = "{{csrf_token()}}";
    var AuthConnect = {!! json_encode(UserConnect() ?? '') !!};
</script>

<script src="{{ asset('backoffice/js/app_script.js') }}"></script>
<script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@stack('footer-script')

<script>

    $(".preloader ").fadeOut();
</script>