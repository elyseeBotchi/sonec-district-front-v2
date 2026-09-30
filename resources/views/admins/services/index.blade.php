@extends('layout.adminApp')

@section('content')
<div class="v2-grid v2-grid--stats">
    @if(CanPermission('entites_voir_les_cartes_valides'))
    <div class="v2-stat-card">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Cartes valides</p>
        <div class="v2-stat-card__value">
            <span id="carte_valide"><span class="v2-spinner v2-spinner--sm"></span></span>
        </div>
    </div>
    @endif

    @if(CanPermission('entites_voir_les_cartes_expirees'))
        <div class="v2-stat-card v2-stat-card--alt">
            <div class="v2-stat-card__top">
                <span class="v2-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </span>
            </div>
            <p class="v2-stat-card__label">Cartes expirées</p>
            <div class="v2-stat-card__value">
                <span id="carte_expirer"><span class="v2-spinner v2-spinner--sm"></span></span>
            </div>
        </div>
    @endif

    @if(CanPermission('entites_voir_les_nouveaux_contrevenants'))
        <div class="v2-stat-card" style="display: none">
            <div class="v2-stat-card__top">
                <span class="v2-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
            </div>
            <p class="v2-stat-card__label">Nouveaux contrevenants</p>
            <div class="v2-stat-card__value">
                <span id="nouveau_contrevenant"><span class="v2-spinner v2-spinner--sm"></span></span>
            </div>
        </div>
    @endif

    @if(CanPermission('entites_voir_tous_les_contrevenants'))
    <div class="v2-stat-card" style="display: none">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Total des contrevenants</p>
        <div class="v2-stat-card__value" id="total_contrevenant">
            <span class="v2-spinner v2-spinner--sm"></span>
        </div>
    </div>
@endif
</div>



@if(CanPermission('entites_voir_la_liste_de_donnee_de_lentite'))
<div class="row col-md-12">
    <div class="col-md-12 col-lg-12">
        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">Liste des véhicules</p>
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
                    <div class="v2-table-wrap">
                        <table class="v2-table" id="datatable-custom">
                            <thead>
                            <tr></tr>
                            </thead>
                            <tbody class="render-html">
                                <tr class="v2-table-loading">
                                    <td><span class="v2-spinner"></span> Chargement des données...</td>
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
    <script src="{{ asset('/backoffice/js/services_listing.js') }}"></script> {{-- --}}
@endpush
@endsection
