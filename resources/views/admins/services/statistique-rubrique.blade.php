@extends('layout.adminApp')

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))
    <div class="row">
        @isset($type_stat)
        @if($type_stat =="paiement")
            @if(canPermission('statistique_voir_le_montant_total_par_jour'))
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

            @if(canPermission('statistique_voir_le_total_des_paiements'))
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

            
        @if(canPermission('statistique_voir_le_montant_total_par_jour_global'))
            <div class="col-md-5"  style="cursor: pointer">
                <div data-status="today" data-pay="all"  class="card card-animate highlight Load_paiement bg-success text-white" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between text-white">
                            <div>
                                <p class="fw-medium text-white mb-0">PAIEMENT DU JOUR GLOBAL</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour_global" style="display: none">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate text-white">
                                    <span class="text-white" id="nb_total_jour_global" style="display: none">
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
                                        <span id="total_paiement_global" style="display: none">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </span>
                                    </h2>
                                    <p class="mb-0 text-muted text-truncate">
                                        <span class="text-white" id="nb_total_global" style="display: none">
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
        @endif
        @endisset 

    </div>
 

            @endif
           
            @if(CanPermission('statistique_partenaires_voir_les_statistiques_par_jour_par_rubrique'))
                @isset($type_stat,$type_sous_stat)
                    @if($type_stat =="rubrique" && $type_sous_stat =="jour")
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
                                                    <th>Montant Total</th> 
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

             
            @isset($type_stat,$type_sous_stat)
                @if(CanPermission('statistique_partenaires_voir_les_statistiques_global_par_rubrique'))
                    @if($type_stat =="rubrique" && $type_sous_stat =="tous")
                        <div class="card">
                            <div class="card-header" id="rubrique-facturation-titre-global">
                                REPARTITION PAR RUBRIQUE DE FACTURATION GLOBAL
                            </div>
                                    <!-- Tableau -->
                                <table class="table table-bordered" id="datatable-rubrique-facturation-global" style="width: 100%">
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

                                <!-- Graphique -->
                                <div class="col-md-12">
                                {{--  <canvas id="facturationChartGlobal" width="800" height="800"></canvas> --}}
                                </div>
                        </div>
                    @endif
                @endif


                @if(CanPermission('statistique_partenaires_voir_les_statistiques_par_mois_par_rubrique'))

                    @if($type_stat =="rubrique" && $type_sous_stat =="mois")
                        <div class="row card">
                            <div class="card-header" id="titre_liste">
                                Historique des paiements par mois
                            </div>
            
                            <div class="pt-5 table-responsive">
                                <table id="tableauStats"  class="table" border="1">
                                    <thead>
                                        <tr id="headerRow">
                                            <th>Rubrique</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                @endif
            @endisset
           



@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
            var type_stat = @json($type_stat ?? '');
            var type_sous_stat = @json($type_sous_stat ?? '');
        </script>
    @endisset
    @if(CanPermission('statistique_voir_le_module_statistique'))
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/statistique_detaille.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
