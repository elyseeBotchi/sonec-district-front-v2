@extends('layout.customerApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">DETAIL DU PAIEMENT</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item active services"><i class="fa fa-spinner fa-spin"></i></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Informations sur le véhicule</h4>

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

        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Informations du paiement</h4>

                    <div class="row">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>TAXE</strong>
                                    </td>
                                    <td id="TaxeEntity"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Place holder for the entity name -->
                                </tr>
                                <tr>
                                    <td>
                                        <strong>référence du paiement</strong>
                                    </td>
                                    <td id="reference"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the current version -->
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Montant payé</strong>
                                    </td>
                                    <td id="montant_paye"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the attribute table name -->
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Mode de paiement</strong>
                                    </td>
                                    <td id="mode_paiement"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the state -->
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Date de paiement </strong>
                                    </td>
                                    <td id="created_at"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the creation date -->
                                </tr>
        
                                <tr>
                                    <td>
                                        <strong>Statut du paiement</strong>
                                    </td>
                                    <td id="status_paiement"> <i class="fa fa-spinner fa-spin"></i> </td> <!-- Placeholder for the update date -->
                                </tr>
                            </tbody>
                        </table>
        
                        <a href="{{ route('landing.entities.taxe.data.generate.file',['uuid' => $paiement_uuid]) }}" class="btn btn-sm btn-success">Télécharger le réçu </a> &nbsp; &nbsp;
                        <span id="info-carte">
                           
                        </span>
                        
                        
                    </div> <!-- end row-->

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


    
@endsection

@push('footer-script')
<script>
    var Paiement_uuid = @Json($paiement_uuid ?? '');
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/front/info_paiement.js') }}"></script> 
@endpush
