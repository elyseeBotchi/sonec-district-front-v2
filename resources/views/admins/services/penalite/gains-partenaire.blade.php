@extends('layout.adminApp')

@section('content')

    <div id="container">
        <div class="v2-grid v2-grid--stats">
            @if (canPermission('acteurs_tiers_voir_le_montant_total_par_jour'))
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
            @endif

            @if(canPermission('acteurs_tiers_voir_le_montant_total'))
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
            @endif
        </div>

        @if(canPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_mensuel'))
            <div class="v2-chart-card" style="margin-top: 20px;">
                <div class="v2-card__header">
                    <p class="v2-card__title">Paiements par mois</p>
                </div>
                <canvas id="chartPaiementMois" class="chart-surface"></canvas>
            </div>
        @endif

        @if(CanPermission('acteurs_tiers_voir_les_statistiques_graphique_par_paiement_journalier'))
            <div class="v2-chart-card" style="margin-top: 20px;">
                <div class="v2-card__header">
                    <p class="v2-card__title">Paiements par jour</p>
                </div>
                <canvas id="chartPaiement" class="chart-surface"></canvas>
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
