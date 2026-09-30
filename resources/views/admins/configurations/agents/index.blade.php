@extends('layout.adminApp')

@section('page-title', 'Agents')
@section('page-subtitle')
    <span class="v2-breadcrumb">Configurations <span>&rsaquo;</span> <strong>Agents</strong></span>
@endsection

@section('content')
    @if(CanPermission('agents_voir_longlet_agent'))
        <div class="row" id="container">
            <div class="col-md-12">
                @if(CanPermission('agents_ajouter_un_agent'))
                    <div class="text-end mb-4">
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#customer-edit_add-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter un agent
                        </a>
                    </div>

                    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendCreateForm" action="{{ route('panel.autorisations.agents.store') }}" method="POST">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Ajouter un agent</p>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="v2-modal-body">
                                    <div style="display:flex; gap:24px; margin-bottom:16px;">
                                        <label style="align-items:center; display:flex; gap:6px; font-size:13.5px;">
                                            <input type="radio" name="civility" id="civility-man" value="m" checked> Monsieur
                                        </label>
                                        <label style="align-items:center; display:flex; gap:6px; font-size:13.5px;">
                                            <input type="radio" name="civility" id="civility-woman" value="mme"> Madame
                                        </label>
                                    </div>

                                    <div style="display:flex; gap:16px; margin-bottom:16px;">
                                        <div style="flex:1;">
                                            <label class="v2-modal-label">Nom <code>*</code></label>
                                            <input type="text" class="v2-modal-input" name="firstname">
                                        </div>
                                        <div style="flex:1;">
                                            <label class="v2-modal-label">Prénom(s) <code>*</code></label>
                                            <input type="text" class="v2-modal-input" name="lastname">
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:16px; margin-bottom:16px;">
                                        <div style="flex:1;">
                                            <label class="v2-modal-label">E-mail <code>*</code></label>
                                            <input type="text" class="v2-modal-input" name="email">
                                        </div>
                                        <div style="flex:1;">
                                            <label class="v2-modal-label">Téléphone <code>*</code></label>
                                            <input type="text" class="v2-modal-input" name="phone">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="v2-modal-label">Matricule <code>*</code></label>
                                        <input type="text" class="v2-modal-input" name="matricule">
                                    </div>
                                </div>
                                <div class="v2-modal-footer">
                                    <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="v2-btn v2-btn--primary">Sauvegarder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif 

                <div class="v2-card">
                    <div class="v2-table-wrap">
                        <table class="v2-table" id="datatable-custom">
                            <thead>
                            <tr>
                                <th>Nom & Prénom(s)</th>
                                <th>E-mail</th>
                                <th>Téléphone</th>
                                <th>Matricule</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr class="v2-table-loading">
                                    <td colspan="6"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif 
@endsection

@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/agents.js') }}"></script>
@endpush
