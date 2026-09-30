@extends('layout.adminApp')

@section('content')

    @if (CanPermission('statistique_partenaires_voir_les_statistiques_par_jour_par_rubrique'))
        @isset($type_stat, $type_sous_stat)
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'jour')
                <div class="v2-card" style="margin-bottom: 20px;">
                    <div class="v2-card__header">
                        <p class="v2-card__title" id="rubrique-facturation-titre">Pénalité encaissée — répartition par jour</p>
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


    @isset($type_stat, $type_sous_stat)
        @if (CanPermission('statistique_partenaires_voir_les_statistiques_global_par_rubrique'))
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'tous')
                <div class="v2-card">
                    <div class="v2-card__header">
                        <p class="v2-card__title" id="rubrique-facturation-titre-global">Pénalité encaissée — répartition par rubrique</p>
                    </div>
                    <div class="v2-table-wrap">
                        <table class="v2-table" id="datatable-rubrique-facturation-global">
                            <thead>
                                <tr>
                                    <th>Rubrique</th>
                                    <th>Nombre</th>
                                    <th class="is-numeric">Montant</th>
                                    <th class="is-numeric">Montant pénalité</th>
                                </tr>
                            </thead>
                            <tbody class="render-html" id="par_facturation-global">
                                <tr class="v2-table-loading">
                                    <td colspan="4"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif


        @if (CanPermission('statistique_partenaires_voir_les_statistiques_par_mois_par_rubrique'))
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'mois')
                <div class="v2-card">
                    <div class="v2-card__header">
                        <p class="v2-card__title" id="titre_liste">Pénalité encaissée — répartition par mois</p>
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
        @if (CanPermission('statistique_voir_le_module_statistique'))
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
            <script src="{{ asset('/backoffice/js/statistique_detaille_penalite.js') }}"></script>
        @endif
    @endpush
@endsection
