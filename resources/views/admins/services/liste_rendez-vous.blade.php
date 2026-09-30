@extends('layout.adminApp')

@section('content')


@if(CanPermission('rendez_vous_rechercher_un_vehicule'))
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="v2-card">
                <div class="v2-card__header">
                    <p class="v2-card__title">Résultat des traitements du jour par nombre et par type</p>
                </div>
                <div class="v2-table-wrap">
                    <table class="v2-table" id="datatable-custom">
                        <thead>
                        <tr>
                            <th>Immatriculation</th>
                            <th>Numero carte grise</th>
                            <th>Type de taxe</th>
                            <th>Statut</th>
                            <th>Heure de traitement</th>
                        </tr>
                        </thead>
                        <tbody class="render-html">
                            <tr class="v2-table-loading">
                                <td colspan="5"><span class="v2-spinner"></span> Chargement des données...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endisset 

@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
    </script>
@endisset

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/services_rdv_liste_recu.js') }}"></script> {{-- --}}
@endpush
@endsection
