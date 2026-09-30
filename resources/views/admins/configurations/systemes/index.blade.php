@extends('layout.adminApp')

@section('page-title', 'Systèmes')
@section('page-subtitle')
    <span class="v2-breadcrumb">Autorisations <span>&rsaquo;</span> <strong>Systèmes</strong></span>
@endsection

@section('content')
        <div class="row" id="container">
            @if(CanPermission('module_voir_longlet_module'))
                <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#createModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter une règle
                        </a>
                    </div>

                    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <form action="{{ route('panel.autorisations.systemes.store') }}" method="POST" class="modal-content sendModuleForm">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Ajouter une règle</p>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="v2-modal-body">
                                    <div style="display:flex; gap:16px; margin-bottom:16px;">
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Date de début <code>*</code></label>
                                            <input type="date" name="start" value="{{ date('Y-m-d') }}" class="v2-modal-input" autofocus>
                                        </div>
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Date de fin</label>
                                            <input type="date" name="end" class="v2-modal-input" />
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:16px; margin-bottom:16px;">
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Ratio (%) <code>*</code></label>
                                            <input type="number" name="rate" class="v2-modal-input" max="100" required />
                                        </div>
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Nombre <code>*</code></label>
                                            <input type="number" name="total_cumul" class="v2-modal-input" required />
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:16px;">
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Type de véhicule</label>
                                            <select name="facturation_uuid" class="v2-modal-input" id="rubrique"></select>
                                        </div>
                                        <div style="flex:1;">
                                            <label for="name" class="v2-modal-label">Montant</label>
                                            <input type="text" name="montant_pay" id="montant_pay" class="v2-modal-input" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="v2-modal-footer">
                                    <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="v2-btn v2-btn--primary">Sauvegarder</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <form action="" method="POST" class="modal-content sendModuleUpdateForm">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Modifier une règle</p>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="v2-modal-body updateModalBody"></div>
                                <div class="v2-modal-footer">
                                    <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="v2-btn v2-btn--primary">Sauvegarder</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="v2-card">
                        <div class="v2-table-wrap">
                            <table class="v2-table" id="datatable-custom">
                                <thead>
                                <tr>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Ratio</th>
                                    <th>Nombre cumul</th>
                                    <th>Type de véhicule</th>
                                    <th>Statut</th>
                                    <th style="width: 25%">Action</th>
                                </tr>
                                </thead>
                                <tbody class="render-html">
                                    <tr class="v2-table-loading">
                                        <td colspan="7"><span class="v2-spinner"></span> Chargement des données...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

@endsection

@if(CanPermission('module_voir_longlet_module'))@endif
    @push('footer-script')
        <script>
            var Entity_uuid = @Json(Entities()[0]['uuid'] ?? '');
        </script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('backoffice/js/systeme.js') }}"></script>
    @endpush

