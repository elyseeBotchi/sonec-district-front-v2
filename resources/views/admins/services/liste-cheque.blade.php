@extends('layout.adminApp')

@section('content')
@if(CanPermission('rendez_vous_rechercher_un_vehicule'))@endif
    <div id="container">
        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">Liste des cotations</p>
            </div>
            <div class="v2-table-wrap">
                <table class="v2-table" id="dataTable">
                    <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Désignation de la cotation</th>
                        <th>Réference de la cotation</th>
                        <th>Contribuable</th>
                        <th>Nombre de véhicule à déclaré</th>
                        <th>Nombre de véhicule enregistré</th>
                        <th>Statut</th>
                        <th style="width:150px">Action</th>
                    </tr>
                    </thead>
                    <tbody id="render-html">
                        <tr class="v2-table-loading">
                            <td colspan="8"><span class="v2-spinner"></span> Chargement des données...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
        var Status = @Json($status ?? '');

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endisset

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/liste_cheque.js') }}"></script>  
@endpush
@endsection
