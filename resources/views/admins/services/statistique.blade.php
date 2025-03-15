@extends('layout.adminApp')

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))
    <div class="row">
        @isset($type_stat)
        @if($type_stat =="paiement")
            @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                <div class="col-md-5" >
                    <div data-status="today" data-pay="all"  class="card card-animate" >
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
                                        <span id="total_paiement_cheque">
                                           {{--  <i class="fa fa-spinner fa-spin"></i> --}}
                                        </span>
                                    </h2>
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" id="nb_total" style="display: block;color:black;">
                                            TOTAL CHEQUE{{-- <i class="fa fa-spinner fa-spin"></i> --}}
                                        </span>
                                    </p>
                                    
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" id="nb_total" style="display: block;color:black;">
                                            TOTAL CARTE VALIDE {{-- <i class="fa fa-spinner fa-spin"></i> --}}
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
                                            {{-- <i class="fa fa-spinner fa-spin"></i> --}}
                                        </span>
                                    </h2>
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" id="nb_total" style="display: block;color:black;">
                                            TOTAL CHEQUE{{-- <i class="fa fa-spinner fa-spin"></i> --}}
                                        </span>
                                    </p>
                                    
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="" id="nb_total" style="display: block;color:black;">
                                            TOTAL CARTE VALIDE {{-- <i class="fa fa-spinner fa-spin"></i> --}}
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

        @if(canPermission('statistique_voir_le_montant_total_par_jour_global'))
            <div class="col-md-5"  style="cursor: pointer">
                <div data-status="today" data-pay="all"  class="card card-animate Load_paiement bg-success text-white" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between text-white">
                            <div>
                                <p class="fw-medium text-white mb-0">PAIEMENT DU JOUR GLOBAL</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour_global">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate text-white">
                                    <span class="text-white" id="nb_total_jour_global">
                                        <i class="fa fa-spinner fa-spin text-white" ></i>
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

    @if(canPermission('statistique_voir_le_total_des_paiements_global'))
        <div class="col-md-5" style="cursor: pointer">
            <div data-status="all" data-pay="all"  class="card card-animate Load_paiement bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium mb-0 text-white">TOTAL PAIEMENTS GLOBAL</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="total_paiement_global">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="text-white" id="nb_total_global">
                                    <i class="fa fa-spinner fa-spin text-white"></i>
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
    </div>

    @if(CanPermission('statistique_voir_les_statistiques_par_operateur'))
            @isset($type_stat)
                @if($type_stat =="operateur")
                    <div class="row align-items-start">
                        <div class="card col-md-12">
                            <div class="card-header" id="">
                                REPARTITION PAR OPERATEURS
                            </div>
                                    <!-- Tableau -->
                            <div class="table-responsive">
                                <table class="table" id="">
                                    <thead>
                                        <tr>
                                            <th>Operateurs</th>
                                            <th>Nombre</th>
                                            <th>Montant Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="render-html" id="par_operateur">
                                        
                                        <tr>
                                            <td> WAVE </td>
                                            <td> 
                                                <span id="wave_nb">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="wave_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td> ORANGE </td>
                                            <td> 
                                                <span id="orange_nb">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="orange_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td> MTN </td>
                                            <td> 
                                                <span id="mtn_nb">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="mtn_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <td> MOOV </td>
                                            <td> 
                                                <span id="moov_nb">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="moov_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td> TRESOR PAY </td>
                                            <td> 
                                                <span id="tresor_nb">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="tresor_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td> TOTAL</td>
                                            <td> 
                                                <span id="nb_total">
                                                <i class=""></i>
                                                </span> 
                                            </td>
                                            <td> 
                                                <span id="total_montant">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                </span> 
                                            </td>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Graphique -->
                        <div class="col-md-12">
                            <canvas id="OperateursChart" width="800" height="800"></canvas>
                        </div>
                    </div>
                @endif
            @endisset  

            @endif
           
            @if(CanPermission('statistique_voir_les_statistiques_par_rubrique'))
                @isset($type_stat)
                    @if($type_stat =="rubrique")
                        <div class="row">
                            <div class="row align-items-start">
                                <div class="card col-md-12">
                                    <div class="card-header" id="rubrique-facturation-titre">
                                        REPARTITION PAR RUBRIQUE DE FACTURATION JOURNALIER
                                    </div>
                                            <!-- Tableau -->
                                    <div class="table-responsive">
                                        <table class="table" id="datatable-rubrique-facturation">
                                            <thead>
                                                <tr>
                                                    <th>Rubrique</th>
                                                    <th>Nombre</th>
                                                    {{-- <th>Montant Total</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="render-html" id="par_facturation">
                                                <tr>
                                                    <td colspan="2"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            
                                <!-- Graphique -->
                                <div class="col-md-12">
                                    <canvas id="facturationChart" width="800" height="800"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset
            @endif

             
            @if(CanPermission('statistique_voir_les_statistiques_par_rubrique_global'))
                @isset($type_stat)
                    @if($type_stat =="rubrique")
                        <div class="row">
                            <div class="row align-items-start">
                                <div class="card col-md-12">
                                    <div class="card-header" id="rubrique-facturation-titre-global">
                                        REPARTITION PAR RUBRIQUE DE FACTURATION
                                    </div>
                                            <!-- Tableau -->
                                    <div class="table-responsive">
                                        <table class="table" id="datatable-rubrique-facturation-global">
                                            <thead>
                                                <tr>
                                                    <th>Rubrique</th>
                                                    <th>Nombre</th>
                                                    <th>Montant Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="render-html" id="par_facturation-global">
                                                <tr>
                                                    <td colspan="4"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            
                                <!-- Graphique -->
                                <div class="col-md-12">
                                    <canvas id="facturationChartGlobal" width="800" height="800"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset
            @endif

        @if(CanPermission('statistique_voir_les_statistiques_par_rendez_vous'))

            @isset($type_stat)
                @if($type_stat =="rdv")
                <div class="row">
                    <div id="rendezvousChart" class="col-md-12"></div>

                    <div class="row align-items-start">
                        <div class="card col-md-12">
                            <div class="card-header" id="rubrique-facturation-titre">
                                LISTE DES DATES RENDEZ-VOUS
                            </div>
                            <!-- Tableau -->
                            <div class="row">
                                <table id="datatable-custom" class="table">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nombre programmé</th>
                                        <th>Nombre effectivement reçu</th> 
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="render-html" id="rdv"> </tbody>
                                </table>
                            </div>
                        </div>

                    
                    
                        <div class="card-header col-md-12" id="titre_rdv">
                            Liste des rendez-vous du jour
                        </div>
                        <table class="table" id="datatable-rdv">
                            <thead>
                                <tr>
                                    <th>Date de RDV</th>
                                    <th>Proprietaire</th>
                                    <th>N° Carte grise</th>
                                    <th>N° Immatriculation</th>
                                    <th>N° Paiement</th>
                                    <th>Montant</th>
                                    <th>Reference</th>
                                    <th>Mode de paiement</th>
                                    <th>ID Transaction</th>
                                    <th>Statut</th>
                                    <th>Date paiement</th>
                                </tr>
                                </thead>
                                <tbody ></tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endisset    

        @endif 

 @if(CanPermission('statistique_voir_les_statistiques_par_paiement'))
    @isset($type_stat)
        @if($type_stat =="paiement")

            <div class="row col-md-12">
                <canvas id="chartPaiementMois" style="width:100% !important;"></canvas>
            </div>

            <br>

            @if(CanPermission('statistique_voir_les_statistiques_par_paiement_detaille'))
                <div class="row col-md-12">
                    <div id="chartPaiement" style="width: 100% !important"></div>
                </div>
            @endif
            <div class="row card" style="display: none">
                <div class="card-header" id="titre_liste">
                    Liste des paiements du jours
                </div>
                <div class="pt-5 table-responsive">
                    <table class="table" id="datatable-custom">
                        <thead>
                            <tr>
                                <th>Proprietaire</th>
                                <th>N° Carte grise</th>
                                <th>N° Immatriculation</th>
                                <th>N° Paiement</th>
                                {{-- <th>Montant</th> --}}
                                <th>Reference</th>
                                <th>Mode de paiement</th>
                                <th>ID Transaction</th>
                                <th>Statut</th>
                                <th>Date paiement</th>
                            </tr>
                            </thead>
                            <tbody ></tbody>
                    </table>
                </div>
            </div>
        @endif
    @endisset
@endif 

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
        <script src="{{ asset('/backoffice/js/statistique.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
