@extends('layout.adminApp')

@section('content')

    <div class="row col-md-12" id="container">
        @if (canPermission('acteurs_tiers_voir_le_montant_total_par_jour'))
            <div class="col-md-6">
                <div data-status="today" data-pay="all" class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">PAIEMENT DU JOUR</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="nb_total_jour" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-activity text-info">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> 
        @endif

        @if(canPermission('acteurs_tiers_voir_le_montant_total'))
            <div class="col-md-6">
                <div data-status="all" data-pay="all" class="card card-animate">
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
                                    <span class="" id="nb_total" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-clock text-info">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div> 
        @endif
     
        @if(canPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_mensuel'))
            <div class="row col-md-12">
                <canvas id="chartPaiementMois" style="width:100% !important;"></canvas>
            </div>
        @endif
        
        <br>
        <br>
        @if(CanPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_journalier'))
            <div class="row col-md-12">
                
                <canvas id="chartPaiement" style="width: 100% !important"></canvas>
            </div>
        @endif
    </div>

    @push('footer-script')
        @isset($Entity_uuid)
            <script>
                var Entity_uuid = @Json($Entity_uuid ?? '');
                var Status = @json($Status ?? '');
            </script>
        @endisset
        @if (CanPermission('acteurs_tiers_voir_mes_gains_en_tant_que_acteur_tiers'))
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
            <script src="{{ asset('/backoffice/js/gain-partenaire.js') }}"></script>
        @endif
    @endpush
@endsection
