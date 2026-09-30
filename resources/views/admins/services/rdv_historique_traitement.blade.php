@extends('layout.adminApp')

@section('content')


@if(CanPermission('rendez_vous_rechercher_un_vehicule'))

<div class="row col-md-12">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">

                <div class="col-md-12">
                    <div class="v2-card">
                        <div class="v2-card__header">
                            <p class="v2-card__title">Résultat des traitements du jour par nombre et par type</p>
                        </div>
                        <div class="v2-table-wrap">
                        <table class="v2-table" id="datatable-traitement">
                            <thead>
                                <tr>
                                    <th>Type de Taxe</th>
                                    <th>Montant de la taxe</th>
                                    <th>Nombre Traité ce jour</th>
                                </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr class="v2-table-loading">
                                    <td colspan="3"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td></td>
                                    <td><strong id="traitement_total"></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
            </div>


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
    <script src="{{ asset('/backoffice/js/services_rdv_historique_traitement.js') }}"></script> {{-- --}}
@endpush
@endsection
