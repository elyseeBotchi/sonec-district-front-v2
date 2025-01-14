@extends('layout.adminApp')

@section('content')
<style>
    .center-form {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Occupe toute la hauteur de la page */
    background-color: #f8f9fa; /* Optionnel : pour un arrière-plan */
}

</style>
@if(CanPermission('rendez_vous_rechercher_un_vehicule'))@endisset 
    <div class="center-form">
        <form class="sendPayForm" action="{{ route('panel.autorisations.services.taxes.caisse.store') }}">
            <h3>FORMULAIRE DE PAIEMENT EN ESPECE</h3>
            @csrf
            <input type="hidden" name="entity_uuid" id="SelectEntity" value="{{ $Entity_uuid ?? '' }}" required />
            <div class="row g-3">
                <div id="form-container" class="col-md-6">
                    <center><i class="fa fa-spinner fa-spin"></i></center> 
                </div>
            
                <div class="col-md-6">
                    <div class="col-md-12">
                        <label class="form-label">Date de la dernière visite <code>*</code></label>
                        <input type="date" class="form-control bg-light border-0" placeholder="" max="{{ date('Y-m-d') }}" name="date_visite" required style="height: 40px;">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Type de véhicule <code>*</code></label>
                        <select class="form-control form-select bg-light border-0 FindLieuRDV" name="rubrique_facturation_uuid" id="rubrique" style="height: 40px;"></select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Montant à payer</label>
                        <input type="text" class="form-control bg-light border-0" placeholder="Montant à payer" id="montant_pay" readonly disabled style="height: 40px;">
                    </div>
                        
                    <div class="col-md-12">
                        <label class="form-label">Lieu de rendez-vous <code>*</code></label>
                        <input type="text" class="form-control bg-light border-0" placeholder="Lieu de rendez vous" id="lieu_rdv" readonly disabled style="height: 40px;">
                        <input type="hidden" name="lieu_rdv" id="list_rdv" required>
                    </div>
                        
                    <div class="col-md-12">
                        <label class="form-label">Date de rendez-vous <code>*</code></label>
                        <select name="rdv" id="rdv" class="form-control bg-light border-0" style="height: 40px;">
                            <option value="{{ date('Y-m-d') }}"> {{ date('d-m-Y') }} </option>
                        </select>
                    </div>

                    
                </div>

                
                <center class="col-md-12"><br>
                    <br>
                    <button class="btn btn-dark" id="submitBtn" type="submit" style="display: none">Payer</button>
                </center>
        </form>
    </div>

@push('footer-script')
@isset($Entity_uuid)
    <script>
        var Entity_uuid = @Json($Entity_uuid ?? '');
    </script>
@endisset

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/service_caisse.js') }}"></script> {{-- --}}
@endpush
@endsection
