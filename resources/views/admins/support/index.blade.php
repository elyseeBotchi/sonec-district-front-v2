@extends('layout.adminApp')

@section('content')



@if(CanPermission('entites_voir_la_liste_de_donnee_de_lentite'))@endisset 
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0"></h4>
                    </div> 

                    <div class="ml-auto">
                        <div class="hide js-show">
                            <div class="card-custom mb-4">
                             

                                <form class="searchData" action="{{ route('panel.autorisations.entities.support.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Rechercher par : </label>
                                                <select class="form-control" name="status">
                                                    <option value="transaction">ID de transaction</option>
                                                    <option value="immatriculation">Numero d'immatriculation</option>
                                                    <option value="nom_du_proprietaire">Nom du propriétaire</option>
                                                    <option value="telephone">Numéro de paiement</option>
                                                    <option value="reference">Réference</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-8">
                                                <label class="form-label"> &nbsp; &nbsp; &nbsp; </label>
                                                <input type="text" class="form-control" id="dateBegin" name="dateBegin" required />
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




@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
    </script>
@endisset
{{--
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/services_listing.js') }}"></script>  --}}
@endpush
@endsection
