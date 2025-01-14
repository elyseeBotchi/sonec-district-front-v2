@extends('layout.adminApp')

@section('content')
<div class="card-group">
    @if(CanPermission('rendez_vous_voir_le_nombre_de_rendez_valide'))
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="rdv_valide"> <i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                        Rendez-vous validés
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted fa fa-address-book fa-2x"></span>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(CanPermission('rendez_vous_voir_le_nombre_de_rendez_rejete'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium" >
                                <span id="rdv_rejete"> <i class="fa fa-spinner fa-spin"></i> </span>
                            </h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                            rendez-vous rejetés
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted fa fa-user-times fa-2x"></span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(CanPermission('rendez_vous_voir_mes_rendez_vous_valide'))
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="mes_rdv_valide"> <i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                      Mes Rendez-vous validés
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted fa fa-address-book fa-2x"></span>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(CanPermission('rendez_vous_voir_mes_rendez_vous_rejete'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <div class="d-inline-flex align-items-center">
                            <h2 class="text-dark mb-1 font-weight-medium" >
                                <span id="mes_rdv_rejete"> <i class="fa fa-spinner fa-spin"></i> </span>
                            </h2>
                        </div>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                          Mes rendez-vous rejetés
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted fa fa-user-times fa-2x"></span>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(CanPermission('rendez_vous_voir_le_nombre_de_rendez_vous_du_jour'))
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                            <span id="rdv_jours"><i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate text-uppercase">
                           Rendez-vous du jours
                        </h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted fa fa-id-badge fa-2x"></span>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>



@if(CanPermission('rendez_vous_rechercher_un_vehicule'))
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">RECEPTION DES USAGERS</h4>
                  
                    </div> 
                    
                    <div class="ml-auto">
                       

                        <div class="hide js-show">
                            <br>
                             <a href="{{ route('panel.autorisations.services.taxes.caisse',['entity_uuid' => $Entity_uuid ]) }}" class="btn btn-rounded btn-outline-primary d-none">
                                <i class="fas fa-plus"></i> PAYER A LA CAISSE
                            </a>

                            <div class="card-custom mb-4">
                                <div class="card-header-custom">
                                    <span id="languageSelectLabel" style="font-size:x-small;">
                                        <br>
                                    </span>


                                </div>

                                <form class="searchForm" action="{{ route('panel.autorisations.services.taxes.rdv.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-11">
                                                <label class="form-label">
                                                    RECHERCHER UN VEHICULE
                                                </label>
                                                <input type="text" class="form-control" name="search" placeholder="Entrez la référence du reçu ou le numero d'immatriculation" required />
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
    <script src="{{ asset('/backoffice/js/services_rdv_listing.js') }}"></script> {{-- --}}
@endpush
@endsection
