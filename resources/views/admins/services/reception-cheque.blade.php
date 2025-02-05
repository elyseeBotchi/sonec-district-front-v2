@extends('layout.adminApp')

@section('content')
<div class="card-group">
    
</div>



@if(CanPermission('rendez_vous_rechercher_un_vehicule'))@endif 
    <div class="row col-md-12">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">RECHERCHER UNE COTATION</h4>
                  
                    </div> 
                    
                    <div class="ml-auto">
                       

                        <div class="hide js-show">
                            <br>
                             {{-- <a href="#" class="btn btn-rounded btn-outline-primary"  data-toggle="modal" data-target="#add-modal">
                                <i class="fas fa-plus"></i> ENREGISTRER UN CHEQUE
                            </a> --}}

                            

                            <div class="card-custom mb-4">
                                <div class="card-header-custom">
                                    <span id="languageSelectLabel" style="font-size:x-small;">
                                        <br>
                                    </span>
                                </div>

                                <form class="searchForm" action="{{ route('panel.autorisations.services.taxes.cheque.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid"  required />
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-11">
                                                <label class="form-label">
                                                    &nbsp; &nbsp; &nbsp; 
                                                </label>
                                                <input type="text" class="form-control" name="search" placeholder="Entrez la référence du cheque" required />
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

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endisset

    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> --}}
    <script src="{{ asset('/backoffice/js/reception-cheque.js') }}"></script>  
@endpush
@endsection
