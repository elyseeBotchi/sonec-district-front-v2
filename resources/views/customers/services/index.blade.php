@extends('layout.customerApp')

@section('content')
    

    <div class="row card" id="container">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0"> <span id="TaxeEntity" style="display:none;"></span></h4>

                {{-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Services</li>
                        <li class="breadcrumb-item active services"><i class="fa fa-spinner fa-spin"></i></li>
                    </ol>
                </div> --}}
            </div>
        </div> 

        <div class="col-sm-12 card-body">
            <div class="text-end mb-4">
               <h4> LISTE DES VEHICULES </h4>
               <a href="#" class="btn btn-rounded btn-outline-primary" data-toggle="modal" data-target="#customer-edit_add-modal">
                    <i class="fas fa-plus"></i> Ajouter un véhicule{{--  un élément à <span class="services"></span> --}}
                </a>

            </div>

            <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendEntiteForm" action="{{ route('customer.entities.taxe.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />

                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Ajouter un véhicule  </h5>{{--  à <span class="services"></span> --}}
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="form-container"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="modal fade" id="updateElement-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendEntiteForm" action="{{ route('customer.entities.taxe.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required />
                        <input type="hidden" name="uuid" id="update-uuid" required />
                        
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Modifier un véhicule  </h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row" id="form-container-update"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <a href="#" data-toggle="modal" data-target="#payElement-modal" id="payElement-modal"> test </a> --}}

            @isset($lock)@endisset  
            <div class="modal fade" id="payElement-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendPayForm" action="{{ route('customer.entities.taxe.facturation.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="pay_uuid" name="pay_uuid" required />
                        <input type="hidden" id="entity_uuid" value="{{ $entity_uuid }}" name="entity_uuid" required />
                        
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Payer <span id="target_pay_name"></span> </h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">  

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fa fa-info-circle me-2" aria-hidden="true"></i>
                                            <strong class="text-uppercase">Informations importantes :</strong>
                                            <ul>
                                                <li>Les tarifs sont valables pour la période du 01/01/{{ date('Y') }} au 31/12/{{ date('Y') }}.</li>
                                                <li>Les tarifs sont soumis à des modifications sans préavis.</li>
                                                <li>Les informations fournies sont sujettes à vérification.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> 

                                <br>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Type de véhicule<code>*</code></label>
                                        <select name="rubrique_facturation_uuid" class="form-control" id="rubrique">
                                            <!-- Les options seront insérées ici par la fonction JavaScript -->
                                        </select>
                                    </div>
                                </div>                                
                                
                                <div class="col-12">
                                    <label class="form-label">Montant à payer</label>
                                    <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer"  id="montant_pay" readonly disabled  style="height: 55px;">
                                </div>

                                                                        
                                <div class="col-12">
                                    <label class="form-label">Montant pénalité</label>
                                    <input type="text" class="form-control bg-light border-0" placeholder="Montant de la pénalité" name="montant_penalite" id="montant_penalite" readonly disabled  style="height: 40px;">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Date de la dernière visite <code>*</code></label>
                                    <input type="date" class="form-control" placeholder="Téléphone de paiement" max="{{ date('Y-m-d') }}"  name="date_visite" required="" required  style="height: 55px;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Lieu de rendez-vous <code>*</code></label>
                                    <select name="lieu_rdv" id="list_rdv" class="form-control">
                                        @isset($lieuRdv)
                                            @forelse($lieuRdv as $key => $value)
                                                <option value="{{ $value['uuid'] }}"> {{ $value['libelle'] ?? '' }} </option>
                                            @empty
                                            @endforelse
                                        @endisset
                                    </select>
                                </div>
                                  
                                <div class="col-12">
                                    <label class="form-label">
                                        Date de rendez-vous <code>*</code>
                                    </label>
                                    <select name="rdv" id="rdv" class="form-control">
                                        @isset($dateValideRdv)
                                            @forelse($dateValideRdv as $key => $value)
                                                @if($key < $limit)
                                                    <option value="{{ $value }}"> {{ date_create($value)->format('d-m-Y') }} </option>
                                                @endif
                                            @empty
                                            @endforelse
                                        @endisset
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Téléphone de paiement <code>*</code></label>
                                        <input type="text" class="form-control" id="num_pay"  name="numero_paiement" required="" minlength="10" maxlength="10" required />
                                    </div>
                                </div>

            
                                <div class="form-group col-12">
                                    <label class="col-form-label">Opérateurs autorisés</label>
                                    <div class="row operator-picker">
                                        @isset($operateurs)
                                            @forelse($operateurs as $operateur)
                                                <label class="col-md-2">
                                                    <img class="img-responsive img-thumbnail" width="64" height="64" src="{{ asset('/operateurs/'.$operateur['logo'] ?? '') }}">
                                                    <span class="operator-name">{{ $operateur['nom'] ?? '' }}</span>
                                                    <input type="radio" name="paymode" value="{{ $operateur['nom_operateur'] ?? '' }}"  />
                                                </label>
                                            @empty
                                                <p>Aucun opérateur disponible.</p>
                                            @endforelse
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary btn-shadow" id="submitBtn">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
            
            
            

            <div class="pt-5 ">
                <table class="table table-striped table-bordered" id="datatable-custom">
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

    
@endsection

@push('footer-script')
<script>
    var Entity_uuid = @Json($entity_uuid);
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/front/services.js') }}"></script>
@endpush
