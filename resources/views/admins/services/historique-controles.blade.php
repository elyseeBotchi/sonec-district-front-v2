@extends('layout.adminApp')

@section('content')

    @if (CanPermission('acteurs_tiers_voir_lhistorique_des_controles_tiers'))
        <div class="row">
            <div class="row col-md-12 align-items-start">
                <div class="card col-md-12">
                    <div class="card-header" id="rubrique-facturation-titre">
                        REPARTITION PAR JOUR
                    </div>
                    <!-- Tableau -->
                    <div class="card-body col-md-12">
                        <!-- Choix de période -->
                        <div class="row">
                            <select id="periodSelect" class="form-control col-md-3">
                                <option value="today" selected>Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                                <option value="range">Plage de date</option>
                            </select> &nbsp;  &nbsp; 

                            <div id="rangePicker" style="display:none; gap:.5rem; margin:.5rem 0;" class="row col-md-8">
                                <input type="date" id="fromDate" class="form-control col-md-3" />
                                <input type="date" id="toDate" class="form-control col-md-3" /> 
                                <button id="applyRange" class="btn btn-primary btn-sm">Appliquer</button>
                            </div>
                        </div>


                        <div id="statsLoading" style="display:none">Chargement…</div>

                        {{-- <div class="table-responsive"> </div> --}}
                            <table id="statsTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Agent</th>
                                        <th>Immatriculation</th>
                                        <th>Type de véhicule</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                       

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
