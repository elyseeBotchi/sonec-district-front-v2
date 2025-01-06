@extends('layout.adminApp')

@section('content')
<div class="card-group">
    @if(CanPermission('entites_voir_les_cartes_valides'))
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="carte_valide"> <i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                        Cartes valides
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted fa fa-address-book fa-2x"></span>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(CanPermission('entites_voir_les_cartes_expirees'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium" >
                                <span id="carte_expirer"> <i class="fa fa-spinner fa-spin"></i> </span>
                            </h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                            Cartes expirées
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted fa fa-user-times fa-2x"></span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(CanPermission('entites_voir_les_nouveaux_contrevenants'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                            <span id="nouveau_contrevenant"><i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                            Nouveaux contrevenants
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted fa fa-id-badge fa-2x"></span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(CanPermission('entites_voir_tous_les_contrevenants'))  
    <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium" id="total_contrevenant"> 
                            <i class="fa fa-spinner fa-spin"></i> 
                        </h2>                        
                    </div>

                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                        Total des contrevenants
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted fa fa-users fa-2x"></span>
                </div>
            </div>
        </div>
    </div>
@endif
</div>



@if(CanPermission('entites_voir_la_liste_de_donnee_de_lentite'))
<div class="row col-md-12">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <h4 class="card-title mb-0">LISTE DES VEHICULES</h4>
                </div> 

                <div class="ml-auto">
                    <div class="hide js-show" style="display: none;">
                        <div class="card-custom mb-4">
                            <div class="card-header-custom">
                                <span id="languageSelectLabel" style="font-size:x-small;">
                                    Filtres
                                </span>
                            </div>

                            <form class="searchData" action="{{ route('panel.autorisations.services.taxes.search') }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                <div class="card-body-custom">
                                    <div class="row col-md-12 billing-section">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">RUBRIQUE DE FACTURATION</label>
                                            <select class="form-control" name="rubrique_facturation_uuid" id="rubrique">
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Date début </label>
                                            <input type="date" value="{{ date('Y-01-01') }}" class="form-control" id="dateBegin" name="dateBegin" required />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Date fin</label>
                                            <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="dateEnd" name="dateEnd" required />
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Résultat </label>
                                            <select class="form-control" name="status">
                                                <option value="">Tous</option>
                                                <option value="success">Valide</option>
                                                <option value="expire">Expiré</option>
                                                <option value="error">Aucun paiement</option>
                                            </select>
                                        </div>
                                    
                                        <div class="form-group col-md-1">
                                            <label class="form-label">&nbsp; &nbsp; &nbsp; </label>
                                            <button type="submit" id="submitBtn" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-primary" title="Rechercher" >
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        
                        </div>
                    </div>
                    <div class="pt-5 table-responsive">
                        <table class="table" id="datatable-custom">
                            <thead>
                            <tr></tr>
                            </thead>
                            <tbody class="render-html">
                                <tr>
                                    <td> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                </tr>
                            </tbody>
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
    <script src="{{ asset('/backoffice/js/services_listing.js') }}"></script> {{-- --}}
@endpush
@endsection
