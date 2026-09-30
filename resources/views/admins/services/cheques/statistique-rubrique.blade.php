@extends('layout.adminApp')

@section('content')
@if(CanPermission('statistique_voir_le_module_statistique'))
    <div class="row">
        @isset($type_stat)
        @if($type_stat =="paiement")
            @if(canPermission('statistique_voir_le_montant_total_par_jour'))
                <div class="col-md-5">
                    <div data-status="today" data-pay="all" class="v2-stat-card">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Paiement du jour</p>
                        <div class="v2-stat-card__value">
                            <span id="montant_total_jour"><span class="v2-spinner v2-spinner--sm"></span></span>
                        </div>
                        <p class="v2-stat-card__sub"><span id="nb_total_jour"><span class="v2-spinner v2-spinner--sm"></span></span></p>
                    </div>
                </div>
            @endif

            @if(canPermission('statistique_voir_le_total_des_paiements'))
                <div class="col-md-5">
                    <div data-status="all" data-pay="all" class="v2-stat-card v2-stat-card--alt">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Total paiements</p>
                        <div class="v2-stat-card__value">
                            <span id="total_paiement"><span class="v2-spinner v2-spinner--sm"></span></span>
                        </div>
                        <p class="v2-stat-card__sub"><span id="nb_total"><span class="v2-spinner v2-spinner--sm"></span></span></p>
                    </div>
                </div>

                {{-- ######################################################################## --}}

                <div class="col-md-5">
                    <div data-status="all" data-pay="all" class="v2-stat-card">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Paiements du jour chèque</p>
                        <div class="v2-stat-card__value">
                            <span id="total_paiement_cheque"></span>
                        </div>
                        <div class="v2-stat-card__meta">
                            <div><span>Total chèque</span><b id="nb_total">TOTAL CHEQUE</b></div>
                            <div><span>Total carte valide</span><b id="nb_total">TOTAL CARTE VALIDE</b></div>
                        </div>
                    </div>
                </div>
                {{-- ############################################################### --}}
                <div class="col-md-5">
                    <div data-status="all" data-pay="all" class="v2-stat-card">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Total paiements chèque</p>
                        <div class="v2-stat-card__value">
                            <span id="total_paiement_cheque"></span>
                        </div>
                        <div class="v2-stat-card__meta">
                            <div><span>Total chèque</span><b id="nb_total">TOTAL CHEQUE</b></div>
                            <div><span>Total carte valide</span><b id="nb_total">TOTAL CARTE VALIDE</b></div>
                        </div>
                    </div>
                </div>
            @endif

            @if(canPermission('statistique_voir_le_montant_total_par_jour_global'))
                <div class="col-md-5">
                    <div data-status="today" data-pay="all" class="v2-stat-card v2-stat-card--highlight Load_paiement">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Paiement du jour global</p>
                        <div class="v2-stat-card__value">
                            <span id="montant_total_jour_global" style="display: none"><span class="v2-spinner v2-spinner--sm"></span></span>
                        </div>
                        <p class="v2-stat-card__sub"><span id="nb_total_jour_global" style="display: none"><span class="v2-spinner v2-spinner--sm"></span></span></p>
                    </div>
                </div>
            @endif

            @if(canPermission('statistique_voir_le_total_des_paiements_global'))
                <div class="col-md-5">
                    <div data-status="all" data-pay="all" class="v2-stat-card v2-stat-card--highlight Load_paiement">
                        <div class="v2-stat-card__top">
                            <span class="v2-stat-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </span>
                        </div>
                        <p class="v2-stat-card__label">Total paiements global</p>
                        <div class="v2-stat-card__value">
                            <span id="total_paiement_global" style="display: none"><span class="v2-spinner v2-spinner--sm"></span></span>
                        </div>
                        <p class="v2-stat-card__sub"><span id="nb_total_global" style="display: none"><span class="v2-spinner v2-spinner--sm"></span></span></p>
                    </div>
                </div>
            @endif
        @endif
        @endisset 

    </div>
 

            @endif
           
            @if(CanPermission('statistique_partenaires_voir_les_statistiques_par_jour_par_rubrique'))
                @isset($type_stat,$type_sous_stat)
                    @if($type_stat =="rubrique" && $type_sous_stat =="jour")
                        <div class="v2-card" style="margin-bottom: 20px;">
                            <div class="v2-card__header">
                                <p class="v2-card__title" id="rubrique-facturation-titre">Chèque encaissé — répartition par jour</p>
                            </div>
                            <div class="v2-table-wrap">
                                <table class="v2-table" id="datatable-rubrique-facturation">
                                    <thead>
                                        <tr>
                                            <th>Rubrique</th>
                                            <th>Nombre</th>
                                            <th class="is-numeric">Montant total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="render-html" id="par_facturation">
                                        <tr class="v2-table-loading">
                                            <td colspan="3"><span class="v2-spinner"></span> Chargement des données...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="v2-chart-card" style="margin-bottom: 20px;">
                            <div class="v2-card__header">
                                <p class="v2-card__title">Répartition graphique</p>
                            </div>
                            <canvas id="facturationChart" class="chart-surface"></canvas>
                        </div>
                    @endif
                @endisset
            @endif

             
            @isset($type_stat,$type_sous_stat)
                @if(CanPermission('statistique_partenaires_voir_les_statistiques_global_par_rubrique'))
                    @if($type_stat =="rubrique" && $type_sous_stat =="tous")
                        <div class="v2-card">
                            <div class="v2-card__header">
                                <p class="v2-card__title" id="rubrique-facturation-titre-global">Chèque encaissé — répartition par rubrique</p>
                            </div>
                            <div class="v2-table-wrap">
                                <table class="v2-table" id="datatable-rubrique-facturation-global">
                                    <thead>
                                        <tr>
                                            <th>Rubrique</th>
                                            <th>Nombre</th>
                                            <th class="is-numeric">Montant total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="render-html" id="par_facturation-global">
                                        <tr class="v2-table-loading">
                                            <td colspan="3"><span class="v2-spinner"></span> Chargement des données...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif


                @if(CanPermission('statistique_partenaires_voir_les_statistiques_par_mois_par_rubrique'))

                    @if($type_stat =="rubrique" && $type_sous_stat =="mois")
                        <div class="v2-card">
                            <div class="v2-card__header">
                                <p class="v2-card__title" id="titre_liste">Chèque encaissé — répartition par mois</p>
                            </div>
                            <div class="v2-table-wrap">
                                <table id="tableauStats" class="v2-table">
                                    <thead>
                                        <tr id="headerRow1">
                                            <!-- Première ligne des en-têtes (Rubrique + Mois fusionnés) -->
                                            <th rowspan="2">Rubrique</th>
                                        </tr>
                                        <tr id="headerRow2">
                                            <!-- Deuxième ligne des en-têtes (Sous-colonnes Nombre et Montant) -->
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <tr class="v2-table-loading">
                                            <td><span class="v2-spinner"></span> Chargement des données...</td>
                                        </tr>
                                    </tbody>
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
        <script src="{{ asset('/backoffice/js/statistique_detaille_cheque.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
