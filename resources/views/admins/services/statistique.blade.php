@extends('layout.adminApp')

@section('content')
<div class="row">
    @if(CanPermission('statistique_paiement_du_jour'))@endif 
        <div class="col-md-3 cursor-pointer">
            <div data-status="today" data-pay="all"  class="card card-animate highlight Load_paiement" >
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">PAIEMENT DU JOUR</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="paiement_jour_montant">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="" id="paiement_jour_nbre">
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
        </div> <!-- end col-->

    @if(CanPermission('statistique_total_wave')) @endif 
        <div class="col-md-3 cursor-pointer">
            <div data-status="all" data-pay="WAVE"  class="card card-animate Load_paiement" >
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">PAIEMENTS WAVE</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="paiement_jour_montant_wave">
                                <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-light text-success mb-0" id="paiement_jour_nbre_wave">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </p>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <img src="{{ asset('operateurs/wave.png') }}"  width="50" height="24" alt="">
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div> <!-- end col-->
    
    @if(CanPermission('statistique_total_orange'))@endif 
        <div class="col-md-3 cursor-pointer">
            <div data-status="all" data-pay="ORANGE"  class="card card-animate Load_paiement">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">PAIEMENTS ORANGE</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="paiement_jour_montant_orange">
                                <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-light text-success mb-0" id="paiement_jour_nbre_orange">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </p>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <img src="{{ asset('operateurs/orange.png') }}"  width="50" height="24" alt="">
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div> <!-- end col-->
    
    @if(CanPermission('statistique_total_mtn'))@endif
        <div class="col-md-3 cursor-pointer">
            <div data-status="all" data-pay="MTN" class="card card-animate Load_paiement">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">PAIEMENTS MTN</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="paiement_jour_montant_mtn">
                                <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="badge bg-light text-success mb-0" id="paiement_jour_nbre_mtn">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </p>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                    <img src="{{ asset('operateurs/mtn.png') }}"  width="50" height="24" alt="">
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div> <!-- end col-->
    
                    
    @if(CanPermission('statistique_total_moov'))@endif
    <div class="col-md-3 cursor-pointer">
        <div data-status="all" data-pay="MOOV" class="card card-animate Load_paiement">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">PAIEMENTS MOOV</p>
                        <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                            <span id="paiement_jour_montant_moov">
                            <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </h2>
                        <p class="mb-0 text-muted text-truncate">
                            <span class="badge bg-light text-success mb-0" id="paiement_jour_nbre_moov">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </p>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                <img src="{{ asset('operateurs/moov.png') }}"  width="50" height="24" alt="">
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

            
    @if(CanPermission('statistique_total_tresor'))@endif
    <div class="col-md-3 cursor-pointer">
        <div data-status="all" data-pay="TRESOR" class="card card-animate Load_paiement">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">PAIEMENTS TRESOR</p>
                        <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                            <span id="paiement_jour_montant_tresor">
                            <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </h2>
                        <p class="mb-0 text-muted text-truncate">
                            <span class="badge bg-light text-success mb-0" id="paiement_jour_nbre_tresor">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </p>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                <img src="{{ asset('operateurs/tresormoney.png') }}"  width="50" height="24" alt="">
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    @if(CanPermission('statistique_total_paiement'))@endif 
    <div class="col-md-3 cursor-pointer">
        <div data-status="all" data-pay="all"  class="card card-animate Load_paiement">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">TOTAL PAIEMENTS</p>
                        <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                            <span id="total_paiement">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </h2>
                        <p class="mb-0 text-muted text-truncate">
                            <span class="badge bg-light text-danger mb-0">
                                {{--<i class="ri-arrow-down-line align-middle"></i> --}}
                            </span>
                        </p>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock text-info"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col--> 
</div>

<div class="row card">
    <div class="card-header" id="titre_liste">
        Liste des paiements
    </div>
    <div class="pt-5 table-responsive">
        <table class="table" id="datatable-custom">
            <thead>
            <tr></tr>
            </thead>
            <tbody class="render-html">
                <tr>
                    <td> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
    </script>
@endisset

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/statistique.js') }}"></script> {{-- --}}
@endpush
@endsection
