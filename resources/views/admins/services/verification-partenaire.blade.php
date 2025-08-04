@extends('layout.adminApp')

@section('content')


@if(CanPermission('acteurs_tiers_voir_le_module_verification_pour_la_penalite'))
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">RECEPTION DES USAGERS POUR LA VISITE TECHNIQUE</h4>
                  
                    </div> 
                    
                    <div class="ml-auto">
                        <div class="hide js-show">
                            <br>
                            <div class="card-custom mb-4">
                                <div class="card-header-custom">
                                    <span id="languageSelectLabel" style="font-size:x-small;">
                                        <br>
                                    </span>
                                </div>

                                <form class="searchForm" action="{{ route('panel.autorisations.services.verification-partenaire.check') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-11">
                                                <label class="form-label">
                                                    RECHERCHER UN VEHICULE
                                                </label>
                                                <input type="text" class="form-control" id="search" name="search" placeholder="Entrez le numero d'immatriculation" required />
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

                                <table class="table">
                                    <tbody id="html_render">
                                        <tr>
                                            <td colspan="2">
                                                {{-- <span class="fa fa-spinner fa-spin"></span> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

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
                                                            
                                                            {{-- <div id="form-container" >
                                                                <i class="fa fa-spinner fa-spin"></i>
                                                            </div> --}}
                                                        
                                                            <div id="form-container" class="col-12">
                                                                {{-- <div class="col-12">
                                                                    <label class="form-label">Nom du proprietaire <code>*</code> </label>
                                                                    <input type="text" class="form-control bg-light border-0" placeholder="Nom du proprietaire" name="nom_du_proprietaire" style="height: 40px;" required="" minlength="3" maxlength="50">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Numero de la carte grise <code>  </code></label>
                                                                    <input type="text" class="form-control bg-light border-0" placeholder="Numero de la carte grise" name="numero_de_la_carte_grise" style="height: 40px;" pattern="^[A-Z]{2}(?[0-9]{6,8})$|^(?[0-9]{6,8}[A-Z]{2}$)|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$" title="Le numéro de la carte grise doit être sous le format AB12345678, 123456AB,1234567AB,12345678AB, ou encore AB-1234-CD">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Numero d'immatriculation <code>*</code> </label>
                                                                    <input type="text" class="form-control bg-light border-0" placeholder="Numero d'immatriculation" id="numero_dimmatriculation" name="numero_dimmatriculation" style="height: 40px;" required="" title="Le numéro d'immatriculation doit être sous le format 1234AB01, 12345WWCI01, AB1234CD, 2020|12345678ABCI12 ou CH3205074">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Telephone <code>*</code> </label>
                                                                    <input type="tel" class="form-control bg-light border-0" placeholder="Telephone" name="telephone" style="height: 40px;" required="" pattern="^(0[1-9]|25)[0-9]{8}$" maxlength="10" title="Le numéro de téléphone doit commencer par 01, 02, 03, ..., ou 25 et contenir exactement 10 chiffres.">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Email <code> Ce mail vous permettra de recevoir vos reçu de paiement </code></label>
                                                                    <input type="email" class="form-control bg-light border-0" placeholder="Email" name="email" style="height: 40px;">
                                                                </div> --}}
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
                                                                    <button class="btn btn-dark w-100 py-3" id="submitBtn" type="submit">Payer</button>{{--  style="display: none" --}}
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
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive"  style="display: none">
        <table class="table table-bordered table-hover text-justify align-middle">
            <thead class="table-primary">
                <tr>
                    {{-- <th class="text-uppercase">#</th> --}}
                    <th class="text-uppercase">RUBRIQUES BUDGETAIRES</th>
                    <th class="text-uppercase">Montant</th>
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
