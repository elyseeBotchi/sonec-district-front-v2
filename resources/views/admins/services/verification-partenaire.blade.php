@extends('layout.adminApp')

@section('content')


@if(CanPermission('acteurs_tiers_voir_le_module_verification_pour_la_penalite'))
    <div class="v2-search-shell">
        <div class="v2-search-card">
            <div class="v2-search-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <p class="v2-search-card__title">Réception des usagers pour la visite technique</p>
            <p class="v2-search-card__desc">Entrez le numéro d'immatriculation du véhicule pour accéder à ses informations.</p>

            <form class="searchForm" action="{{ route('panel.autorisations.services.verification-partenaire.check') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />

                <div class="v2-search-card__group">
                    <label class="v2-search-card__label" for="search">Numéro d'immatriculation</label>
                    <div class="v2-search-card__input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                        <input type="text" id="search" name="search" placeholder="Entrez le numero d'immatriculation" required />
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="v2-btn v2-btn--navy v2-btn--block">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div id="html_render-toolbar" class="text-end mb-3" style="display: none;">
        <button type="button" id="html_render-print" class="v2-btn v2-btn--navy">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Imprimer la carte
        </button>
    </div>
    <div id="html_render"></div>

    <div id="formulaire" style="display: none;">
        <div class="col-md-12 d-flex align-items-center justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-danger" id="penaltyGate" style="display: none;" role="alert">
                    <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                    <strong class="text-uppercase">PENALITE DEJA ENREGISTRE :</strong>
                    <ul>
                        <li> STRUCTURE: <span id="penalite_structure"></span></li>
                        <li> DATE DE DEBUT: <span id="penalite_date"></span></li>
                        <li>NUMERO D'IMMATRICULATION: <span id="penalite_dimmatriculation"></span></li>
                   </ul>
                </div>


                <div class="bg-primary rounded h-100 p-5 wow">
                    <form  class="sendPayForm" action="{{ route('landing.entities.taxe.payment') }}">
                        <h3>FORMULAIRE DE PAIEMENT</h3>
                        <div class="alert alert-info" role="alert" id="formulaire">
                            <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                            <strong class="text-uppercase">Informations importantes :</strong>
                            <ul>
                                <li>Les tarifs sont valables pour la période du 01/01/{{ date('Y') }} au 31/12/{{ date('Y') }}.</li>
                                <li>Les tarifs sont soumis à des modifications sans préavis.</li>
                                <li>Les informations fournies sont sujettes à vérification.</li>
                            </ul>
                        </div>
                        <br>

                        @csrf
                        <input type="hidden"  name="entity_uuid" id="SelectEntity" value="{{ $Entity_uuid ?? '' }}" required />
                            <div class="row g-3">

                                <div id="form-container" class="col-12">
                                </div>

                                <div class="col-12">
                                    <div class="col-12">
                                        <label class="form-label">Date de la dernière visite <code>*</code></label>
                                        <input type="date" class="form-control bg-light border-0" placeholder="" max="{{ date('Y-m-d') }}"  name="date_visite" required="" required style="height: 40px;">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Type de véhicule <code>*</code></label>
                                        <select class="form-control form-select bg-light border-0 FindLieuRDV" name="rubrique_facturation_uuid" id="rubrique" style="height: 40px;"></select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Montant à payer</label>
                                        <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 40px;">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Montant pénalité</label>
                                        <input type="text" class="form-control bg-light border-0" placeholder="Montant de la pénalité"  id="montant_penalite" readonly disabled  style="height: 40px;">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Lieu de rendez-vous <code>*</code></label>
                                        <input type="text" class="form-control bg-light border-0" placeholder="Lieu de rendez vous"  id="lieu_rdv" readonly disabled  style="height: 40px;">
                                        <input type="hidden"  name="lieu_rdv" id="list_rdv" required style="height: 40px;">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Date de rendez-vous <code>*</code>
                                        </label>
                                        <select name="rdv" id="rdv" class="form-control bg-light border-0" style="height: 40px;">
                                            @isset($viewData['dateValideRdv'])
                                                @isset($viewData['dateValideRdv'][0])
                                                    @forelse($viewData['dateValideRdv'] as $key => $value)
                                                        @if($key < $viewData['limit'])
                                                            <option value="{{ $value }}"> {{ date_create($value)->format('d-m-Y') }} </option>
                                                        @endif
                                                    @empty
                                                    <option value="{{ date('Y-m-d') }}"> {{ date('d-m-Y') }} </option>
                                                    @endforelse
                                                    @else
                                                    <option value="{{ date('Y-m-d') }}"> {{ date('d-m-Y') }} </option>
                                                @endisset
                                            @endisset
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Téléphone de paiement <code>*</code></label>
                                        <input type="tel" class="form-control bg-light border-0" placeholder="Téléphone de paiement"  id="num_pay"  name="numero_paiement" required="" minlength="10" maxlength="10" required style="height: 40px;">
                                    </div>

                                    <div class="col-12">
                                        <label for="prenoms" class="col-form-label">Opérateurs autorisés </label>
                                        <div class="row">
                                            @isset($viewData['operateurs'])
                                                @isset($viewData['operateurs'][0])
                                                    @forelse($viewData['operateurs'] as $operateur)
                                                        <label class="col-md-2">
                                                            <input type="radio" name="paymode" value="{{ $operateur['nom_operateur'] ?? '' }}"  />
                                                            <img class="img-responsive img-thumbnail" width="64" height="64" src="{{ asset('/operateurs/'.$operateur['logo'] ?? '') }}">
                                                            {{ $operateur['nom'] ?? '' }}
                                                        </label>
                                                    @empty
                                                        <p>Aucun opérateur disponible.</p>
                                                    @endforelse
                                                @endisset
                                            @endisset
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-dark w-100 py-3" id="submitBtn" type="submit">Payer</button>
                                    </div>
                                </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="v2-table-wrap" style="display: none">
        <table class="v2-table">
            <thead>
                <tr>
                    <th>Rubriques budgétaires</th>
                    <th class="is-numeric">Montant</th>
                </tr>
            </thead>
            <tbody id="tarif_line"></tbody>
        </table>
    </div>
@endisset

@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
        var penalty_date_begin = '';
    </script>
@endisset

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/front/landingPage.js') }}"></script>

    <script src="{{ asset('/backoffice/js/services_verification_partenaire_listing.js') }}"></script> {{-- --}}


@endpush
@endsection
