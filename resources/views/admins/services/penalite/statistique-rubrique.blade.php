@extends('layout.adminApp')

@section('content')

    @if (CanPermission('statistique_partenaires_voir_les_statistiques_par_jour_par_rubrique'))
        @isset($type_stat, $type_sous_stat)
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'jour')
                <div class="row">
                    <div class="row align-items-start">
                        <div class="card col-md-12">
                            <div class="card-header" id="rubrique-facturation-titre">
                                PENALITE ENCAISSE | REPARTITION PAR JOUR
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
                                            <td colspan="2"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ...
                                            </td>
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


    @isset($type_stat, $type_sous_stat)
        @if (CanPermission('statistique_partenaires_voir_les_statistiques_global_par_rubrique'))
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'tous')
                <div class="card">
                    <div class="card-header" id="rubrique-facturation-titre-global">
                        PENALITE ENCAISSE | REPARTITION PAR RUBRIQUE
                    </div>
                    <!-- Tableau -->
                    <table class="table table-bordered" id="datatable-rubrique-facturation-global" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Rubrique</th>
                                <th>Nombre</th>
                                <th>Montant</th>
                                <th>Montant pénalité</th>
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


        @if (CanPermission('statistique_partenaires_voir_les_statistiques_par_mois_par_rubrique'))
            @if ($type_stat == 'rubrique' && $type_sous_stat == 'mois')
                <div class="row card">
                    <div class="card-header" id="titre_liste">
                        PENALITE ENCAISSE | REPARTITION PAR MOIS
                    </div>

                    <div class="pt-5 table-responsive">
                        <table id="tableauStats" border="1" class="table" cellspacing="0" cellpadding="5">
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
                                <!-- Les données seront insérées ici dynamiquement -->
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
