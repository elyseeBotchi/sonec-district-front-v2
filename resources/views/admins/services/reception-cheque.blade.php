@extends('layout.adminApp')

@section('content')

@if(CanPermission('rendez_vous_rechercher_un_vehicule'))
    <div class="v2-search-shell">
        <div class="v2-search-card">
            <div class="v2-search-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <p class="v2-search-card__title">Rechercher une cotation</p>
            <p class="v2-search-card__desc">Entrez la référence du chèque pour accéder aux détails de la cotation et du véhicule.</p>

            <form class="searchForm" action="{{ route('panel.autorisations.services.taxes.cheque.search') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid" required />

                <div class="v2-search-card__group">
                    <label class="v2-search-card__label" for="cheque-search-input">Référence du chèque</label>
                    <div class="v2-search-card__input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                        <input type="text" id="cheque-search-input" name="search" placeholder="Entrez la référence du chèque" required />
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="v2-btn v2-btn--navy v2-btn--block">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Rechercher la cotation
                </button>
            </form>

            <div id="cheque-last-search-block" style="display: none;">
                <div class="v2-search-card__divider"></div>
                <p class="v2-search-card__quick-label">Accès rapide</p>
                <div class="v2-search-card__quick-grid">
                    <a href="#" id="cheque-last-search-item" class="v2-search-card__quick-item">
                        <span class="v2-search-card__quick-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </span>
                        <span>
                            <span class="v2-search-card__quick-title" style="display:block;">Dernière recherche</span>
                            <span class="v2-search-card__quick-sub" id="cheque-last-search-value"></span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

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
