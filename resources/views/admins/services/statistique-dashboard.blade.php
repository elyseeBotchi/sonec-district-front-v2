@extends('layout.adminApp')

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))
    <div class="row">
        @isset($type_stat)
        @if($type_stat =="paiement")
            @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                <div class="col-md-5" >
                    <div data-status="today" data-pay="all"  class="card card-animate highlight" >
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            @endif

            @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                <div class="col-md-5" >
                    <div data-status="all" data-pay="all"  class="card card-animate">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock text-info"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col--> 



                {{-- ######################################################################## --}}

                <div class="col-md-5" >
                    <div data-status="all" data-pay="all"  class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">PAIEMENTS DU JOUR CHEQUE</p>
                                    <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                        <span id="total_paiement_cheque_j">
                                            <i class="fa fa-spinner fa-spin"></i> {{-- --}}
                                        </span>
                                    </h2>
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class=""  style="display: block;color:black;">
                                            TOTAL CHEQUE : <b id="nb_total_cheque_j"><i class="fa fa-spinner fa-spin"></i></b>  {{-- --}}
                                        </span>
                                    </p>
                                    
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" style="display: block;color:black;">
                                            TOTAL CARTE VALIDE : <b id="nb_total_carte_j"><i class="fa fa-spinner fa-spin"></i></b>   {{-- --}}
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

                {{-- ############################################################### --}}
                <div class="col-md-5" >
                    <div data-status="all" data-pay="all"  class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">TOTAL PAIEMENTS CHEQUE</p>
                                    <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                        <span id="total_paiement_cheque">
                                            <i class="fa fa-spinner fa-spin"></i> {{-- --}}
                                        </span>
                                    </h2>
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" style="display: block;color:black;">
                                            TOTAL CHEQUE : <b id="nb_total_cheque">  <i class="fa fa-spinner fa-spin" ></i></b> {{-- --}}
                                        </span>
                                    </p>
                                    
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class=""   style="display: block;color:black;">
                                            TOTAL CARTE VALIDE : <b id="total_carte_valide_cheque"><i class="fa fa-spinner fa-spin"></i></b>  {{----}}
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
            @endif
        @endif
        @endisset 

    </div>


        @isset($type_stat)
            @if($type_stat =="paiement")

                @if(canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel'))
                    <div class="row col-md-12">
                        <canvas id="chartPaiementMois" style="width:100% !important;"></canvas>
                    </div>
                @endif 
                <br>
                @if(CanPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier'))
                    <div class="row col-md-12">
                        <div id="chartPaiement" style="width: 100% !important"></div>
                    </div>
                @endif        
            @endif
        @endisset
    

    @if(CanPermission('statistique_voir_les_statistiques_par_periode'))

        @isset($type_stat)
            @if($type_stat =="periode")

                <div class="row col-md-12">
                    <div id="periodeChart" style="width: 100% !important"></div>
                </div>


                <div class="row card">
                    <div class="card-header" id="titre_liste">
                        Historique des paiements par période
                    </div>

                    <div class="pt-5 table-responsive">
                        <table class="table" id="datatable-periode">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nombre de paiement</th>
                                    <th>Montant total</th>
                                </tr>
                                </thead>
                                <tbody id="render_periode"></tbody>
                                <tfoot>
                                    <tr style="display: none">
                                        <th>Total</th>
                                        <th ></th>
                                        <th id="montant_total_periode"></th>
                                    </tr>
                                </tfoot>
                        </table>
                    </div>
                </div>
                
            @endif
        @endisset
        @endif

    @endif 


@if(CanPermission('statistique_voir_les_statistiques_par_agent_validateur'))
    @isset($type_stat)
        @if($type_stat =="validation_jour")
            <div class="row col-md-12">
                <div id="chartvalidationJ" style="width: 100% !important"></div>
            </div>

            <div class="row card">
                <div class="card-header" id="titre_validation_jour">
                </div>
                <div class="pt-5 table-responsive">
                    <table class="table" id="datatable-validationJ">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Nombre</th>
                            </tr>
                            </thead>
                            <tbody ></tbody>
                    </table>
                </div>
            </div>
        @endif
    @endisset
@endif 


@if(CanPermission('statistique_voir_les_statistiques_par_agent_validateur'))
@isset($type_stat)
    @if($type_stat =="agent_validateur")
        <div class="row col-md-12">
            <div id="chartvalidateur" style="width: 100% !important"></div>
        </div>


        <div class="row col-md-12">
            <div id="chartCamembert" style="width: 100% !important"></div>
        </div>

        <div class="row card">
            <div class="card-header" id="titre_validateur">
                HISTIORIQUE DES VALIDATIONS PAR AGENTS
            </div>
            <div class="pt-5 table-responsive">
                <table class="table" id="datatable-validateur">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Nombre</th>
                        </tr>
                        </thead>
                        <tbody ></tbody>
                </table>
            </div>
        </div>
    @endif
@endisset
@endif 

@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
            var type_stat = @json($type_stat ?? '');
        </script>
    @endisset
    @if(CanPermission('statistique_voir_le_module_statistique'))
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/statistique_detaille.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
