@extends('layout.adminApp')

@section('content')

    @if (CanPermission('acteurs_tiers_voir_lhistorique_des_controles_tiers'))
        <div class="row">
            <div class="row col-md-12 align-items-start">
                <div class="v2-card col-md-12">
                    <div class="v2-card__header">
                        <p class="v2-card__title" id="rubrique-facturation-titre">Répartition par jour</p>
                    </div>
                    <div class="col-md-12">
                        <!-- Choix de période -->
                        <div style="align-items: flex-end; display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
                            <div style="min-width: 200px;">
                                <label class="v2-modal-label">Période</label>
                                <select id="periodSelect" class="v2-modal-input">
                                    <option value="today" selected>Aujourd'hui</option>
                                    <option value="week">Cette semaine</option>
                                    <option value="month">Ce mois</option>
                                    <option value="range">Plage de date</option>
                                </select>
                            </div>

                            <div id="rangePicker" class="v2-range-picker" style="display: none;">
                                <div>
                                    <label class="v2-modal-label">Du</label>
                                    <input type="date" id="fromDate" class="v2-modal-input" />
                                </div>
                                <div>
                                    <label class="v2-modal-label">Au</label>
                                    <input type="date" id="toDate" class="v2-modal-input" />
                                </div>
                                <button id="applyRange" class="v2-btn v2-btn--navy">Appliquer</button>
                            </div>
                        </div>

                        <div id="statsLoading" class="v2-table-loading" style="display:none">
                            <span class="v2-spinner"></span> Chargement des données...
                        </div>

                        <div class="v2-table-wrap">
                            <table id="statsTable" class="v2-table">
                                <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Agent</th>
                                        <th>Immatriculation</th>
                                        <th>Type de véhicule</th>
                                        <th>Pénalité appliqué</th>
                                        <th>Payé</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>


            
            </div>
        </div>
    @endif





    @push('footer-script')
        @isset($Entity_uuid)
            <script>
                var Entity_uuid = @Json($Entity_uuid ?? '');
                var type_stat = @json($type_stat ?? '');
                var type_sous_stat = @json($type_sous_stat ?? '');
            </script>
        @endisset
        @if (CanPermission('acteurs_tiers_voir_lhistorique_des_controles_tiers'))
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
            <script src="{{ asset('/backoffice/js/tentative_verification_penalite.js') }}"></script>
        @endif
    @endpush
@endsection
