@extends('layout.adminApp')

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))
    <div class="row">
        @isset($type_stat)
        @if($type_stat =="paiement")

            <table class="table table-reponsive table-striped table-bordered">
                <thead>
                    <tr  class="table-success">
                        <th>#</th>
                        <th>Paiement du jour</th>
                        <th>Nombre de Paiement du jour (Cartes validés)</th>
                        <th>Paiement total</th>
                        <th>Nombre total de carte</th>
                    </tr>   
                </thead>

                <tr>
                    <td>Espèce</td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                            <span id="montant_total_jour">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                            <span class="" id="nb_total_jour">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                            <span id="total_paiement">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                            <span class="" id="nb_total">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Chèque </td>
                    <td>
                        <span id="total_paiement_cheque_j">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="nb_total_carte_j">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="total_paiement_cheque">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="total_carte_valide_cheque">
                           <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span style="display: none">
                            <b id="nb_total_cheque">  <i class="fa fa-spinner fa-spin" ></i></b>
                        </span>
                    </td>
                </tr>
                
                
                <tr style="display: none">
                    <td>Pénalité</td>
                    <td>
                        <span id="total_penalite_j">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="nb_total_penalite_j">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="total_paiement_penalite">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </td>
                    <td>
                        <span id="total_nbre_penalite">
                           <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span style="display: none">
                            <b id="nb_total_penalite">  <i class="fa fa-spinner fa-spin" ></i></b>
                        </span>
                    </td>
                </tr>

                <tr class="table-active">
                    <td> <b>Cumul</b> </td>
                    <td>
                       <b>
                            <span id="cumul_paiements_jour">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </b> 
                    </td>
                    <td>
                        <b>
                            <span id="cumul_nbre_paiements_jour">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>                           
                        </b>
                    </td>
                    <td>
                        <b>
                            <span id="cumul_paiements">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>                  
                        </b>

                    </td>
                    <td>
                        <b>
                            <span id="cumul_carte_valide">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>                     
                        </b>

                    </td>
                </tr>
            </table>
            
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
                    <div class="row col-md-12" style="display: none">
                        <div id="chartPaiement" style="width: 100% !important"></div>
                    </div>
                @endif        
            @endif
        @endisset
    

    @if(CanPermission('statistique_partenaires_voir_les_statistiques_par_periode'))

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
        <script src="{{ asset('/backoffice/js/statistique_detaille_cheque.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
