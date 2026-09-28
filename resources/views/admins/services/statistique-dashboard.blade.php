@extends('layout.adminApp')

@section('page-subtitle', "Voici un aperçu de vos paiements aujourd'hui.")

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))

    <div class="v2-grid v2-grid--stats">
        @isset($type_stat)
        @if($type_stat =="paiement")
            @if(canPermission('statistique_partenaires_voir_le_montant_total_par_jour'))
                <div data-status="today" data-pay="all" class="v2-stat-card">
                    <div class="v2-stat-card__top">
                        <span class="v2-stat-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </span>
                    </div>
                    <p class="v2-stat-card__label">Paiement du jour mobile</p>
                    <div class="v2-stat-card__value">
                        <span id="montant_total_jour"><i class="fa fa-spinner fa-spin"></i></span>
                        <span class="unit">FCFA</span>
                    </div>
                    <p class="v2-stat-card__sub"><span id="nb_total_jour"><i class="fa fa-spinner fa-spin"></i></span> paiement(s)</p>
                </div>
            @endif

            @if(canPermission('statistique_partenaires_voir_le_montant_total'))
                <div data-status="all" data-pay="all" class="v2-stat-card v2-stat-card--alt">
                    <div class="v2-stat-card__top">
                        <span class="v2-stat-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                    </div>
                    <p class="v2-stat-card__label">Total paiements mobile</p>
                    <div class="v2-stat-card__value">
                        <span id="total_paiement"><i class="fa fa-spinner fa-spin"></i></span>
                        <span class="unit">FCFA</span>
                    </div>
                    <p class="v2-stat-card__sub"><span id="nb_total"><i class="fa fa-spinner fa-spin"></i></span> paiement(s)</p>
                </div>

                {{-- ######################################################################## --}}

                <div data-status="all" data-pay="all" class="v2-stat-card">
                    <div class="v2-stat-card__top">
                        <span class="v2-stat-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </span>
                    </div>
                    <p class="v2-stat-card__label">Paiements du jour chèque</p>
                    <div class="v2-stat-card__value">
                        <span id="total_paiement_cheque_j"><i class="fa fa-spinner fa-spin"></i></span>
                        <span class="unit">FCFA</span>
                    </div>
                    <div class="v2-stat-card__meta">
                        <div><span>Total chèque</span><b id="nb_total_cheque_j"><i class="fa fa-spinner fa-spin"></i></b></div>
                        <div><span>Total carte valide</span><b id="nb_total_carte_j"><i class="fa fa-spinner fa-spin"></i></b></div>
                    </div>
                </div>

                {{-- ############################################################### --}}
                <div data-status="all" data-pay="all" class="v2-stat-card">
                    <div class="v2-stat-card__top">
                        <span class="v2-stat-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                    </div>
                    <p class="v2-stat-card__label">Total paiements chèque</p>
                    <div class="v2-stat-card__value">
                        <span id="total_paiement_cheque"><i class="fa fa-spinner fa-spin"></i></span>
                        <span class="unit">FCFA</span>
                    </div>
                    <div class="v2-stat-card__meta">
                        <div><span>Total chèque</span><b id="nb_total_cheque"><i class="fa fa-spinner fa-spin"></i></b></div>
                        <div><span>Total carte valide</span><b id="total_carte_valide_cheque"><i class="fa fa-spinner fa-spin"></i></b></div>
                    </div>
                </div>
            @endif

            <div data-status="all" data-pay="all" class="v2-stat-card">
                <div class="v2-stat-card__top">
                    <span class="v2-stat-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </span>
                </div>
                <p class="v2-stat-card__label">Total pénalité</p>
                <div class="v2-stat-card__value">
                    <span id="total_penalite"><i class="fa fa-spinner fa-spin"></i></span>
                    <span class="unit">FCFA</span>
                </div>
                <p class="v2-stat-card__sub"><span id="nb_total_penalite"><i class="fa fa-spinner fa-spin"></i></span> pénalité(s)</p>
            </div>

        @endif
        @endisset

    </div>


        @isset($type_stat)
            @if($type_stat =="paiement")

                @if(canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel'))
                    <div class="v2-chart-card" style="margin-top: 16px;">
                        <div class="v2-card__header">
                            <div>
                                <p class="v2-card__title">Paiements mobile — vue mensuelle</p>
                                <p class="v2-card__subtitle">Évolution des paiements mobile money</p>
                            </div>
                        </div>
                        <canvas id="chartPaiementMois" class="chart-surface"></canvas>
                    </div>
                @endif
                @if(CanPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier'))
                    <div class="v2-chart-card" style="margin-top: 16px;">
                        <div class="v2-card__header">
                            <div>
                                <p class="v2-card__title">Aperçu des paiements</p>
                                <p class="v2-card__subtitle">Détail journalier</p>
                            </div>
                        </div>
                        <div id="chartPaiement" class="chart-surface"></div>
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
        <script src="{{ asset('/backoffice/js/statistique_detaille.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
