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
                                <h4 class="card-title text-center">DETAIL DE L'ACTIVITE DU JOUR </h4>
                            </div>
                            <table class="table">
                                <tbody class="render-html">
                                    <tr>
                                        <td> 
                                           
                                         </td>
                                         <td> 
                                             <h3>
                                                TRAITES
                                             </h3>  
                                         </td>
                                         <td> 
                                             <h3>
                                                REJETES
                                             </h3>  
                                         </td>
                                         <td> 
                                             <h3>
                                                TOTAL
                                             </h3>  
                                         </td>
                                    </tr>    
                                    
                                    <tr>
                                        <td> 
                                            <h3>
                                                Usagers reçus  et ayant  RDV ce Jour 			
                                            </h3>
                                         </td>
                                         <td> 
                                             <span id="avec_rdv_valide"></span>
                                         </td>
                                         <td> 
                                            <span id="avec_rdv_rejete"></span>
                                         </td>
                                         <td> 
                                            <span id="avec_rdv_total"></span>
                                         </td>
                                    </tr> 

                                    <tr>
                                        <td> 
                                            <h3>
                                               Usager reçus sans RDV reçu 			
                                            </h3>
                                         </td>
                                         <td> 
                                            <span id="sans_rdv_valide"></span>
                                        </td>
                                        <td> 
                                           <span id="sans_rdv_rejete"></span>
                                        </td>
                                        <td> 
                                           <span id="sans_rdv_total"></span>
                                        </td>
                                    </tr>      
                              
                                </tbody>
                            </table>
                        </div> 
                </div>


                    <div class="ml-auto">
                  
                        <div class="d-flex align-items-start">
                            <h4 class="card-title mb-0">
                                RESULTAT DES TRAITEMENTS JOURNALIERS PAR NOMBRE ET PAR TYPE
                            </h4>
                        </div> 
        
                        <table class="table" id="datatable-custom">
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
    <script src="{{ asset('/backoffice/js/services_rdv_activite.js') }}"></script> {{-- --}}
@endpush
@endsection
