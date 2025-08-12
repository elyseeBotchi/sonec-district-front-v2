@extends('layout.adminApp')

@section('content')


    @if (CanPermission('statistiques_de_penalites_voir_les_gains_des_acteurs_tiers'))
        <div class="row">
            @isset($type_stat)
                @if ($type_stat == 'paiement')
                    <table class="table table-reponsive table-striped table-bordered">
                        <thead>
                            <tr class="table-success">
                                <th>Partenaires techniques</th>
                                <th>Nombre total de carte</th>
                                <th>Montant cartes</th>
                                <th>Pénalité</th>
                                <th>% partenaire</th>
                                <th>Part partenaire</th>
                                <th>Part District</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                @endif
            @endisset

        </div>


        @isset($type_stat)
            @if ($type_stat == 'paiement')
                @if (canPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_mensuel'))
                    <div class="row col-md-12">
                        <canvas id="chartPaiementMois" style="width:100% !important;"></canvas>
                    </div>
                @endif
                <br>
                @if (CanPermission('statistique_partenaires_voir_les_statistiques_graphique_par_paiement_journalier'))
                    <div class="row col-md-12" style="display: none">
                        <div id="chartPaiement" style="width: 100% !important"></div>
                    </div>
                @endif
            @endif
        @endisset

        @if (CanPermission('statistiques_de_penalites_voir_les_statistiques_par_periode'))
            @isset($type_stat)
                @if ($type_stat == 'periode')
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
                                        <th>Montant pénalité</th>
                                        <th>Montant cartes</th>
                                        <th>Montant total (PENALITE + TAXE) </th>
                                    </tr>
                                </thead>
                                <tbody id="render_periode"></tbody>
                                <tfoot>
                                    <tr style="display: none">
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
