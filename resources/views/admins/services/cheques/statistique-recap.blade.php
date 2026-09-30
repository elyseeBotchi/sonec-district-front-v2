@extends('layout.adminApp')

@section('content')

    <div id="container">
        <div class="v2-grid v2-grid--stats">

            <div data-status="fail" class="v2-stat-card v2-stat-card--clickable Load_cheque">
                <div class="v2-stat-card__top">
                    <span class="v2-stat-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </span>
                </div>
                <p class="v2-stat-card__label">Chèques annulés</p>
                <div class="v2-stat-card__value">
                    <span id="cheque_annule"><span class="v2-spinner v2-spinner--sm"></span></span>
                </div>
                <p class="v2-stat-card__sub"><span id="cheque_annule_nbre"><span class="v2-spinner v2-spinner--sm"></span></span></p>
            </div>

            <div data-status="cotation" class="v2-stat-card v2-stat-card--clickable v2-stat-card--alt Load_cheque">
                <div class="v2-stat-card__top">
                    <span class="v2-stat-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </span>
                </div>
                <p class="v2-stat-card__label">Facture proforma en cours</p>
                <div class="v2-stat-card__value">
                    <span id="cheque_previsionnel"><span class="v2-spinner v2-spinner--sm"></span></span>
                </div>
                <p class="v2-stat-card__sub"><span id="cheque_previsionnel_nbre"><span class="v2-spinner v2-spinner--sm"></span></span></p>
            </div>

            <div data-status="pending" class="v2-stat-card v2-stat-card--clickable highlight Load_cheque">
                <div class="v2-stat-card__top">
                    <span class="v2-stat-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </span>
                </div>
                <p class="v2-stat-card__label">Chèques déposés & non encaissés</p>
                <div class="v2-stat-card__value">
                    <span id="cheque_depose"><span class="v2-spinner v2-spinner--sm"></span></span>
                </div>
                <p class="v2-stat-card__sub"><span id="cheque_depose_nbre"><span class="v2-spinner v2-spinner--sm"></span></span></p>
            </div>

            <div data-status="validate" class="v2-stat-card v2-stat-card--clickable Load_cheque">
                <div class="v2-stat-card__top">
                    <span class="v2-stat-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </span>
                </div>
                <p class="v2-stat-card__label">Chèques encaissés</p>
                <div class="v2-stat-card__value">
                    <span id="cheque_encaisse"><span class="v2-spinner v2-spinner--sm"></span></span>
                </div>
                <p class="v2-stat-card__sub"><span id="cheque_encaisse_nbre"><span class="v2-spinner v2-spinner--sm"></span></span></p>
            </div>

        </div>
        <div class="v2-card" style="margin-top: 20px;">
            <div class="v2-card__header">
                <p class="v2-card__title" id="titre_liste"></p>
            </div>

            <div class="v2-table-wrap">
                <table class="v2-table" id="dataTable"></table>
            </div>
        </div>
    </div>

@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
            var Status = @json($Status ?? '');
        </script>
    @endisset
    @if(CanPermission('statistique_voir_le_module_statistique'))
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/recap_cheque.js') }}"></script> {{-- --}}
    @endif
@endpush
@endsection
