@extends('layout.adminApp')

@section('content')


@if(CanPermission('rendez_vous_rechercher_un_vehicule'))
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="ml-auto">
                  
                        <div class="d-flex align-items-start">
                            <h4 class="card-title mb-0">LISTE DES USAGERS REÇU</h4>
                        </div> 
        
                        <table class="table" id="datatable-custom">
                            <thead>
                            <tr>
                                <td>
                                    Immatriculation 
                                </td>
                                <td>
                                    Numero carte grise   
                                </td>
                                <td>
                                    Type de taxe 
                                </td>
                                <td>
                                    Statut
                                </td>
                                <td>
                                    Heure de traitement
                                </td>
                            </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr>
                                    <td colspan="5"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                </tr>
                            </tbody>
                        </table>
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
    <script src="{{ asset('/backoffice/js/services_rdv_liste_recu.js') }}"></script> {{-- --}}
@endpush
@endsection
