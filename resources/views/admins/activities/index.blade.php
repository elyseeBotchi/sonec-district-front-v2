@extends('layout.adminApp')

@section('content')
<div class="v2-grid v2-grid--stats">
    @if(CanPermission('activites_voir_les_scannes_journaliers'))
    <div class="v2-stat-card">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Pénalités journalier</p>
        <div class="v2-stat-card__value">
            <span id="penalite_journalier"><span class="v2-spinner v2-spinner--sm"></span></span>
        </div>
    </div>
    @endif

    @if(CanPermission('activites_voir_le_nombre_de_scanne_mensuel'))
    <div class="v2-stat-card v2-stat-card--alt">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Scanne journalier</p>
        <div class="v2-stat-card__value">
            <span id="scanne_journalier"><span class="v2-spinner v2-spinner--sm"></span></span>
        </div>
    </div>
    @endif

    @if(CanPermission('activites_voir_les_penalites_mensuel'))
    <div class="v2-stat-card">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Pénalité mensuel</p>
        <div class="v2-stat-card__value">
            <span id="penalite_mensuel"><span class="v2-spinner v2-spinner--sm"></span></span>
        </div>
    </div>
    @endif

    @if(CanPermission('activites_voir_le_nombre_de_scanne_mensuel'))
    <div class="v2-stat-card v2-stat-card--alt">
        <div class="v2-stat-card__top">
            <span class="v2-stat-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            </span>
        </div>
        <p class="v2-stat-card__label">Scanne mensuel</p>
        <div class="v2-stat-card__value">
            <span id="scanne_mensuel"><span class="v2-spinner v2-spinner--sm"></span></span>
        </div>
    </div>
    @endif
</div>

@if(CanPermission('activites_voir_lhistorique_des_controles'))
    <div class="v2-grid v2-grid--2col" style="margin-top: 20px; align-items: start;">
        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">Recherche</p>
                <p class="v2-card__subtitle">Filtrer l'historique des contrôles</p>
            </div>

            <form class="searchData" action="{{ route('panel.autorisations.activity.agents.search') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label class="v2-modal-label">Agent</label>
                    <select class="v2-modal-input" name="agent" id="agent"></select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="v2-modal-label">Date début</label>
                    <input type="date" value="{{ date('Y-m-01') }}" class="v2-modal-input" id="dateBegin" name="dateBegin" required />
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="v2-modal-label">Date fin</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="v2-modal-input" id="dateEnd" name="dateEnd" required />
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="v2-modal-label">Résultat</label>
                    <select class="v2-modal-input" name="status">
                        <option value="">Tous</option>
                        <option value="success">Valide</option>
                        <option value="expire">Expiré</option>
                        <option value="error">Incorrect</option>
                    </select>
                </div>

                <button type="submit" class="v2-btn v2-btn--navy v2-btn--block">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Rechercher
                </button>
            </form>
        </div>

        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">Historique des contrôles</p>
            </div>
            <div class="v2-table-wrap">
                <table class="v2-table" id="datatable-custom">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Date</th>
                            <th>QR Code</th>
                            <th>Bien</th>
                            <th>Résultat</th>
                        </tr>
                    </thead>
                    <tbody class="render-html">
                        <tr class="v2-table-loading">
                            <td colspan="5"><span class="v2-spinner"></span> Chargement des données...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/activities.js') }}"></script> {{-- --}}
@endpush

@endsection
