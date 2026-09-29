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
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                            <span class="" id="nb_total_jour">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                            <span id="total_paiement">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                            <span class="" id="nb_total">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Chèque </td>
                    <td>
                        <span id="total_paiement_cheque_j">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="nb_total_carte_j">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="total_paiement_cheque">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="total_carte_valide_cheque">
                           <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                        <span style="display: none">
                            <b id="nb_total_cheque">  <span class="v2-spinner v2-spinner--sm"></span></b>
                        </span>
                    </td>
                </tr>
                
                
                <tr style="display: none">
                    <td>Pénalité</td>
                    <td>
                        <span id="total_penalite_j">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="nb_total_penalite_j">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="total_paiement_penalite">
                            <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                    </td>
                    <td>
                        <span id="total_nbre_penalite">
                           <span class="v2-spinner v2-spinner--sm"></span>
                        </span>
                        <span style="display: none">
                            <b id="nb_total_penalite">  <span class="v2-spinner v2-spinner--sm"></span></b>
                        </span>
                    </td>
                </tr>

                <tr class="table-active">
                    <td> <b>Cumul</b> </td>
                    <td>
                       <b>
                            <span id="cumul_paiements_jour">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>
                        </b> 
                    </td>
                    <td>
                        <b>
                            <span id="cumul_nbre_paiements_jour">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>                           
                        </b>
                    </td>
                    <td>
                        <b>
                            <span id="cumul_paiements">
                                <span class="v2-spinner v2-spinner--sm"></span>
                            </span>                  
                        </b>

                    </td>
                    <td>
                        <b>
                            <span id="cumul_carte_valide">
                                <span class="v2-spinner v2-spinner--sm"></span>
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

                <div class="v2-toolbar">
                    <h1 class="v2-toolbar__title" id="titre_liste">Historique des paiements par période</h1>
                    <div class="v2-toolbar__actions">
                        <div class="v2-tabs" id="v2-periode-tabs">
                            <button type="button" class="v2-tabs__item is-active" data-granularity="jour">Quotidien</button>
                            <button type="button" class="v2-tabs__item" data-granularity="mois">Mensuel</button>
                            <button type="button" class="v2-tabs__item" data-granularity="annee">Annuel</button>
                        </div>
                        <button type="button" id="v2-periode-export" class="v2-btn v2-btn--navy">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Exporter PDF
                        </button>
                    </div>
                </div>

                <div class="v2-chart-card" style="margin-bottom: 20px;">
                    <div class="v2-card__header">
                        <div>
                            <p class="v2-card__title">Volume des paiements</p>
                            <p class="v2-card__subtitle">Analyse comparative du montant des transactions</p>
                        </div>
                    </div>
                    <div id="periodeChart" class="chart-surface"></div>
                </div>

                <div class="v2-card">
                    <div class="v2-card__header">
                        <p class="v2-card__title">Détails de la période</p>
                        <span class="v2-card__meta" id="v2-periode-range"></span>
                    </div>

                    <div class="v2-table-wrap">
                        <table class="v2-table" id="datatable-periode">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nombre de paiement</th>
                                    <th class="is-numeric">Montant total</th>
                                    <th class="is-numeric">Progression</th>
                                </tr>
                                </thead>
                                <tbody id="render_periode">
                                    <tr class="v2-table-loading">
                                        <td colspan="4"><span class="v2-spinner"></span> Chargement des données...</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>

                    <div class="v2-card__footer v2-card__footer--split">
                        <button type="button" class="v2-link-btn" id="v2-periode-more">Afficher plus de résultats</button>
                        <span class="v2-card__meta" id="v2-periode-pagination"></span>
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
                            <tbody>
                                <tr class="v2-table-loading">
                                    <td colspan="2"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
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
                        <tbody>
                            <tr class="v2-table-loading">
                                <td colspan="2"><span class="v2-spinner"></span> Chargement des données...</td>
                            </tr>
                        </tbody>
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
