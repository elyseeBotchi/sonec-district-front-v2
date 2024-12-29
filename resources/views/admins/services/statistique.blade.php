@extends('layout.adminApp')

@section('content')
<div class="row">
    @if(CanPermission('statistique_paiement_du_jour'))@endif 
        <div class="col-md-3 cursor-pointer">
            <div data-status="today" data-pay="all"  class="card card-animate highlight Load_paiement" >
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">PAIEMENT DU JOUR</p>
                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                                <span id="montant_total_jour">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </h2>
                            <p class="mb-0 text-muted text-truncate">
                                <span class="" id="nb_total_jour">
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
        </div> <!-- end col-->


    @if(CanPermission('statistique_total_paiement'))@endif 
    <div class="col-md-3 cursor-pointer">
        <div data-status="all" data-pay="all"  class="card card-animate Load_paiement">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">TOTAL PAIEMENTS</p>
                        <h2 class="mt-4 ff-secondary cfs-22 fw-semibold">
                            <span id="total_paiement">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </h2>
                        <p class="mb-0 text-muted text-truncate">
                            <span class="" id="nb_total">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </p>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock text-info"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div> <!-- end card-->
    </div> <!-- end col--> 
</div>




    <div class="row align-items-start">
        <div class="card col-md-8">
            <div class="card-header" id="">
                REPARTITION PAR OPERATEURS
            </div>
                    <!-- Tableau -->
            <div class="table-responsive">
                <table class="table" id="">
                    <thead>
                        <tr>
                            <th>Operateurs</th>
                            <th>Nombre</th>
                            <th>Montant Total</th>
                        </tr>
                    </thead>
                    <tbody class="render-html" id="par_operateur">
                        
                        <tr>
                            <td> WAVE </td>
                            <td> 
                                <span id="wave_nb">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                            <td> 
                                <span id="wave_montant">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                        </tr>

                        
                        <tr>
                            <td> ORANGE </td>
                            <td> 
                                <span id="orange_nb">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                            <td> 
                                <span id="orange_montant">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                        </tr>
                        
                        <tr>
                            <td> MTN </td>
                            <td> 
                                <span id="mtn_nb">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                            <td> 
                                <span id="mtn_montant">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                        </tr>
                        
                        
                        <tr>
                            <td> MOOV </td>
                            <td> 
                                <span id="moov_nb">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                            <td> 
                                <span id="moov_montant">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                        </tr>

                        
                        <tr>
                            <td> TRESOR PAY </td>
                            <td> 
                                <span id="tresor_nb">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                            <td> 
                                <span id="tresor_montant">
                                 <i class="fa fa-spinner fa-spin"></i>
                                </span> 
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Graphique -->
        <div class="col-md-4">
            <canvas id="OperateursChart" width="400" height="400"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="row align-items-start">
            <div class="card col-md-8">
                <div class="card-header" id="rubrique-facturation-titre">
                    REPARTITION PAR RUBRIQUE DE FACTURATION
                </div>
                        <!-- Tableau -->
                <div class="table-responsive">
                    <table class="table" id="datatable-rubrique-facturation">
                        <thead>
                            <tr>
                                <th>Rubrique</th>
                                <th>Nombre</th>
                                <th>Montant Total</th>
                            </tr>
                        </thead>
                        <tbody class="render-html" id="par_facturation">
                            <tr>
                                <td colspan="4"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        
            <!-- Graphique -->
            <div class="col-md-4">
                <canvas id="facturationChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>


    <div class="row card" style="display: none">
        <div class="card-header" id="titre_liste">
            Liste des paiements
        </div>
        <div class="pt-5 table-responsive">
            <table class="table" id="datatable-custom">
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>N° Paiement</th>
                        <th>Montant</th>
                        <th>Reference</th>
                        <th>Mode de paiement</th>
                        <th>ID Transaction</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody ></tbody>
            </table>
        </div>
    </div>


@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
    </script>
@endisset

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/statistique.js') }}"></script> {{-- --}}
@endpush
@endsection
