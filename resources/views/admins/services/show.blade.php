@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">
               <span id="TaxeEntity"> <i class="fa fa-spinner fa-spin"></i></span>
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Entité</li>
                    <li class="breadcrumb-item active">Détail</li>
                </ol>
            </div>

        </div>
    </div>

    <div  id="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Informations du Bien</h4>
    
                        <div class="row">
                            <table class="table">
                                <tbody id="html_render">
                                    <tr>
                                        <td colspan="2">
                                            <span class="fa fa-spinner fa-spin"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div> 
            </div> 

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Historique des paiements</h4>
    
                        <div class="row table-responsive">
                            <table class="table table-striped" id="history_container">
                                <thead>
                                    <tr>
                                        
                                        <td>
                                            <strong>Date de paiement </strong>
                                        </td>
                                        <td>
                                            <strong>TAXE</strong>
                                        </td>
                                        <td>
                                            <strong>référence du paiement</strong>
                                        </td>
                                        <td>
                                            <strong>Montant payé</strong>
                                        </td>
                                        <td>
                                            <strong>Mode de paiement</strong>
                                        </td>
            
                                        <td>
                                            <strong>Statut du paiement</strong>
                                        </td>
                                        {{-- <td>
                                            Action
                                        </td> --}}
                                    </tr>
                                </thead>
                                <tbody id="history_render">
                                    <tr class="border-0">
                                        <td colspan="7"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div> 
            </div> 
        </div>
    </div>
@endsection


@push('footer-script')
    <script>
        var Element_uuid = @Json($element_uuid);
        var Entity_uuid = @Json($entity_uuid);
        /* ########################################################## */
    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/services_show.js') }}"></script>
@endpush
