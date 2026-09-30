@extends('layout.adminApp')

@section('content')


    @if (CanPermission('statistiques_de_penalites_voir_les_gains_des_acteurs_tiers'))
        @isset($type_stat)
            @if ($type_stat == 'paiement')
                <div class="v2-card" style="margin-bottom: 20px;">
                    <div class="v2-card__header">
                        <p class="v2-card__title">Répartition par partenaire technique</p>
                    </div>
                    <div class="v2-table-wrap">
                        <table class="v2-table">
                            <thead>
                                <tr>
                                    <th>Partenaires techniques</th>
                                    <th>Nombre total de carte</th>
                                    <th class="is-numeric">Montant cartes</th>
                                    <th class="is-numeric">Pénalité</th>
                                    <th class="is-numeric">% partenaire</th>
                                    <th class="is-numeric">Part partenaire</th>
                                    <th class="is-numeric">Part District</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                @if (canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel'))
                    <div class="v2-chart-card" style="margin-bottom: 20px;">
                        <div class="v2-card__header">
                            <p class="v2-card__title">Parts partenaire et District par mois</p>
                        </div>
                        <canvas id="chartPaiementMois" class="chart-surface"></canvas>
                    </div>
                @endif

                @if (CanPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier'))
                    <div class="v2-chart-card" style="display: none; margin-bottom: 20px;">
                        <div class="v2-card__header">
                            <p class="v2-card__title">Paiements par jour</p>
                        </div>
                        <div id="chartPaiement" class="chart-surface"></div>
                    </div>
                @endif
            @endif
        @endisset
        @if (CanPermission('statistiques_de_penalites_voir_les_statistiques_par_periode'))
            @isset($type_stat)
                @if ($type_stat == 'periode')

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
                                <p class="v2-card__title">Volume des pénalités</p>
                                <p class="v2-card__subtitle">Analyse comparative du montant des pénalités</p>
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
                                        <th>Nombre</th>
                                        <th class="is-numeric">Montant carte</th>
                                        <th class="is-numeric">Pénalité enlèvement</th>
                                        <th class="is-numeric">Pénalité fourrière</th>
                                        <th class="is-numeric">Total général</th>
                                        <th class="is-numeric">Progression</th>
                                    </tr>
                                </thead>
                                <tbody id="render_periode">
                                    <tr class="v2-table-loading">
                                        <td colspan="7"><span class="v2-spinner"></span> Chargement des données...</td>
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

    @push('footer-script')
        @isset($Entity_uuid)
            <script>
                var Entity_uuid = @Json($Entity_uuid ?? '');
                var type_stat = @json($type_stat ?? '');
            </script>
        @endisset
        @if (CanPermission('statistiques_de_penalites_voir_les_gains_des_acteurs_tiers'))
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
            @isset($type_stat)
                @if($type_stat == 'paiement')
                    <script src="{{ asset('/backoffice/js/statistique_penalite_gains.js') }}"></script>
                @else
                    <script src="{{ asset('/backoffice/js/statistique_detaille_penalite.js') }}"></script>
                @endif
            @endisset
        @endif
    @endpush
@endsection
