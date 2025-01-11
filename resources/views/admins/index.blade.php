@extends('layout.adminApp')

@section('content')
<div class="card-group">
    @isset($lock)
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="paiement_journalier">
                               8
                            </span>
                        </h2>
                        {{-- <span class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span> --}}
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pénalités journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">153</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Nombre de scanne journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                </div>
            </div>
        </div>
    </div>
    @endisset
    

    @if(CanPermission('tableau_de_bord_voir_les_validations_en_attentes'))

        <div class="card">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium" id="validation_pending">
                                <span class="fa fa-spinner fa-spin"></span>
                            </h2>
                        </div>

                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">
                        Validation en attente
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                    </div>
                </div>
            </div>
        </div>
    @endif 

    @if(CanPermission('tableau_de_bord_voir_les_paiements_du_jour'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium" id="nb_total_jour">
                            <span class="fa fa-spinner fa-spin"></span>
                        </h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Paiement du jour
                        </h6>
                    </div>
                
                </div>
            </div>
        </div>
    @endif 

    
   
</div>

<div class="row col-md-12">
    {{-- <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Evolution des paiements </h4>
                <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                <ul class="list-inline text-center mt-5 mb-2">
                    <li class="list-inline-item text-muted font-italic">
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}

    @if(CanPermission('tableau_de_bord_voir_mes_statistiques_de_validation'))
        <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">RENDEZ-VOUS DU JOUR </h4>
                    </div>
                    <table class="table" id="datatable-custom">
                        <tbody class="render-html">
                            <tr>
                                <td> <h3>Date</h3> </td>
                                <td> 
                                    <h3>
                                        {{ date('d-m-Y') }}
                                    </h3>  
                                </td>
                            </tr>                   
                            <tr>
                                <td>
                                    <h3>
                                        Total RDV
                                    </h3>
                                
                                </td>
                                <td> 
                                    <h3 id="total_rdv_jour"></h3> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>
                                        Reçu par l'agent
                                    </h3>
                                </td>
                                <td>
                                    <h3 id="rdv_recu_jour"></h3>     
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
        </div>
    @endif

        

    @if(CanPermission('statistique_voir_les_statistiques_par_rendez_vous'))

                <div class="row">
                    <div class="row">
                        @if(canPermission('statistique_voir_le_montant_total_par_jour'))
                        <div class="col-md-6 cursor-pointer"  style="cursor: pointer;display:none;">
                            <div data-status="today" data-pay="all"  class="card card-animate highlight Load_paiement" >
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
                                                <span class="" id="nb_total_jour">
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
                        <div class="col-md-6 cursor-pointer" style="cursor: pointer;display:none;">
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
                                                <span class="" id="nb_total">
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
                        @endif
                    </div>
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
    
@if(CanPermission('tableau_de_bord_recap_des_activites_du_jour'))
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-center">DETAIL DE L'ACTIVITE DU JOUR </h4>
                            </div>
                            <table class="table">
                                <tbody class="render-html">
                                    <tr>
                                        <td> 
                                        
                                        </td>
                                        <td> 
                                            <h3>
                                                VALIDES
                                            </h3>  
                                        </td>
                                        <td> 
                                            <h3>
                                                REJETES
                                            </h3>  
                                        </td>
                                        <td> 
                                            <h3>
                                                TOTAL
                                            </h3>  
                                        </td>
                                    </tr>    
                                    
                                    <tr>
                                        <td> 
                                            <h3>
                                                Usagers ayant RDV			
                                            </h3>
                                        </td>
                                        <td> 
                                            <span id="avec_rdv_valide"></span>
                                        </td>
                                        <td> 
                                            <span id="avec_rdv_rejete"></span>
                                        </td>
                                        <td> 
                                            <span id="avec_rdv_total"></span>
                                        </td>
                                    </tr> 

                                    <tr>
                                        <td> 
                                            <h3>
                                            UsagerS sans RDV 			
                                            </h3>
                                        </td>
                                        <td> 
                                            <span id="sans_rdv_valide"></span>
                                        </td>
                                        <td> 
                                        <span id="sans_rdv_rejete"></span>
                                        </td>
                                        <td> 
                                        <span id="sans_rdv_total"></span>
                                        </td>
                                    </tr>      
                            
                                </tbody>
                            </table>
                        </div> 
                    </div>


                    <div class="ml-auto">
                
                        <div class="d-flex align-items-start">
                            <h4 class="card-title mb-0">
                                RESULTAT DES TRAITEMENTS PAR NOMBRE ET PAR TYPE
                            </h4>
                        </div> 
        
                        <table class="table" id="datatable-traitement">
                            <thead>
                            <tr>
                                <td>
                                    <h3>
                                        Type de Taxe
                                    </h3>
                                    
                                </td>
                                <td>
                                    <h3>
                                         Montant de la taxe
                                    </h3>
                                    
                                </td>
                                <td>
                                    <h3>
                                        Nombre Traité ce jour
                                    </h3>
                                    
                                </td>
                            
                            </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr>
                                    <td colspan="3"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <h3>
                                            TOTAL
                                        </h3>
                                        
                                    </td>
                                    <td></td>
                                    <td>
                                        <h3 id="traitement_total"></h3>
                                    </td>
                                
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endif 


</div>

@push('footer-script')
    <script>
        var Entity_uuid = @Json(Entities()[0]['uuid'] ?? '');
        var type_stat = @json('rdv' ?? '');

      //  alert(Entity_uuid)
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/dashboard-admin.js') }}"></script> {{-- --}}
    @if(CanPermission('statistique_voir_le_module_statistique'))
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/statistique.js') }}"></script> {{-- --}}
@endif    
@endpush


@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
        </script>
    @endisset
     
@endpush
@endsection
