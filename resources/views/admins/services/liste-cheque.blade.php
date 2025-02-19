@extends('layout.adminApp')

@section('content')
<div class="card-group">
    
</div>



@if(CanPermission('rendez_vous_rechercher_un_vehicule'))@endif 
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">LISTE DES COTATIONS</h4>
                  
                    </div> 
                    
                    <div class="ml-auto">
                       
                        <div class="pt-5 ">
                            <table class="table table-striped table-bordered" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Désignation de la cotation</th>
                                    <th>Réference de la cotation</th>
                                    <th>Contribuable</th>
                                    <th>Nombre de véhicule à déclaré</th>
                                    <th>Nombre de véhicule enregistré</th>
                                    <th>Statut</th>
                                    <th style="width:150px !important;">Action</th>
                                </tr>
                                </thead>
                                <tbody id="render-html">
                                    <tr>
                                        <td colspan="8"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    
                </div>
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
