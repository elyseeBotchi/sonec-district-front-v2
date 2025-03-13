@extends('layout.adminApp')

@section('content')
    <div class="row col-md-12" id="container">
        <div class="row col-md-12">
        

            <div class="col-md-4" >
                <div data-status="today" data-pay="all"  class="card card-animate highlight" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">CHEQUES ANNULES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="nb_total_jour" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>


            <div class="col-md-4" >
                <div data-status="today" data-pay="all"  class="card card-animate highlight" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">COTATIONS PREVISIONNELS</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="nb_total_jour" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

                
            <div class="col-md-4" >
                <div data-status="today" data-pay="all"  class="card card-animate highlight" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">CHEQUES DEPOSES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="nb_total_jour" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

            <div class="col-md-4" >
                <div data-status="today" data-pay="all"  class="card card-animate highlight" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">CHEQUES ENCAISSES</p>
                                <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                    <span id="montant_total_jour">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </h2>
                                <p class="mb-0 text-muted text-truncate">
                                    <span class="" id="nb_total_jour" style="display: block;color:black;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div> <!-- end card-->
            </div>

        </div>
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">HISTORIQUE DES COTATIONS</h4>
                
                    </div> 
                    
                    <div class="ml-auto">
                    
                        <div class="pt-5 ">
                            <table class="table table-striped table-bordered" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Numéro du chèque</th>
                                    <th>Réference de la cotation</th>
                                    <th>Banque émettrice </th>
                                    <th>Date d'émission </th>
                                    <th>Date d'encaissement </th>
                                    <th>Montant chèque</th>
                                    <th>Titulaire du compte</th>
                                    <th>Statut</th>
                                    <th style="width:150px !important;">Action</th>
                                </tr>
                                </thead>
                                <tbody id="render-html">
                                    <tr>
                                        <td colspan="10"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
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
            var Status = @json($Status ?? '');
        </script>
    @endisset
    @if(CanPermission('statistique_voir_le_module_statistique'))
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/recap_cheque.js') }}"></script> {{-- --}}
    @endif     
@endpush
@endsection
