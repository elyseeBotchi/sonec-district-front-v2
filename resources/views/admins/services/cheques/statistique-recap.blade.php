@extends('layout.adminApp')

@section('content')

    <div class="row col-md-12" id="container">
        <div class="row col-md-12">
        

            <div class="col-md-4" >
                <div data-status="fail" class="card card-animate Load_cheque" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p>CHEQUES ANNULES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="cheque_annule">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="cheque_annule_nbre" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>


            <div class="col-md-4" >
                <div data-status="cotation" class="card card-animate Load_cheque" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p>COTATIONS PRÉVISIONNELLES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="cheque_previsionnel">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="cheque_previsionnel_nbre" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

                
            <div class="col-md-4" >
                <div data-status="pending" class="card card-animate highlight Load_cheque" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p>CHEQUES DEPOSES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="cheque_depose">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="cheque_depose_nbre" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

            <div class="col-md-4" >
                <div data-status="validate"class="card card-animate Load_cheque" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p>CHEQUES ENCAISSES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="cheque_encaisse">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="cheque_encaisse_nbre" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

        </div>
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0" id="titre_liste"></h4>
                
                    </div> 
                    
                    <div class="ml-auto">
                    
                        <div class="pt-5 table-responsive">
                            <table class="table table-striped table-bordered" id="dataTable"></table>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
           
@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
            var Status = @json($Status ?? '');
        </script>
    @endisset
    @if(CanPermission('statistique_voir_le_module_statistique'))
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/recap_cheque.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
