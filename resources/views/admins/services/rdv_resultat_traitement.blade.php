@extends('layout.adminApp')

@section('content')


@if(CanPermission('rendez_vous_rechercher_un_vehicule'))

<div class="row col-md-12">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">RESULTAT DES TRAITEMENTS DU JOUR PAR NOMBRE ET PAR TYPE</h4>
                        </div>
                        <table class="table" id="datatable-traitement">
                            <thead>
                                <tr>
                                    <td>
                                        Type de Taxe
                                    </td>
                                    <td>
                                        Montant de la taxe 
                                    </td>
                                    <td>
                                        Nombre Traité ce jour
                                    </td>
                                    
                                </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr>
                                    <td colspan="3"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <h3>
                                            TOTAL
                                        </h3>
                                        
                                    </td>
                                    <td></td>
                                    <td>
                                        <h3 id="traitement_total"></h3>
                                    </td>
                                
                                </tr>
                            </tfoot>
                        </table>
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
    <script src="{{ asset('/backoffice/js/services_rdv_resultat_traitement.js') }}"></script> {{-- --}}
@endpush
@endsection
