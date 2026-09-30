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
    <div class="v2-search-shell">
        <div class="v2-search-card">
            <div class="v2-search-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <p class="v2-search-card__title">Rechercher un véhicule</p>
            <p class="v2-search-card__desc">Entrez la référence du reçu ou le numéro d'immatriculation pour accéder aux détails du paiement et du véhicule.</p>

            @if(CanPermission('rendez_vous_recevoir_les_paiements_cash'))
                <a href="{{ route('panel.autorisations.services.taxes.caisse',['entity_uuid' => $Entity_uuid ]) }}" class="btn btn-rounded btn-outline-primary d-none">
                    <i class="fas fa-plus"></i> PAYER A LA CAISSE
                </a>
            @endif

            <form class="searchForm" action="{{ route('panel.autorisations.services.taxes.rdv.search') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid" required />

                <div class="v2-search-card__group">
                    <label class="v2-search-card__label" for="rdv-search-input">Référence du reçu ou numéro d'immatriculation</label>
                    <div class="v2-search-card__input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                        <input type="text" id="rdv-search-input" name="search" placeholder="Ex: 5075GC01 ou DIS|TSA-26..." required />
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="v2-btn v2-btn--navy v2-btn--block">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Rechercher le véhicule
                </button>
            </form>

            <div id="rdv-last-search-block" style="display: none;">
                <div class="v2-search-card__divider"></div>
                <p class="v2-search-card__quick-label">Accès rapide</p>
                <div class="v2-search-card__quick-grid">
                    <a href="#" id="rdv-last-search-item" class="v2-search-card__quick-item">
                        <span class="v2-search-card__quick-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                        <span>
                            <span class="v2-search-card__quick-title" style="display:block;">Dernière recherche</span>
                            <span class="v2-search-card__quick-sub" id="rdv-last-search-value"></span>
                        </span>
                    </a>
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
