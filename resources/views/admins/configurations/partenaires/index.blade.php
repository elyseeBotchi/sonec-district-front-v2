@extends('layout.adminApp')

@section('page-title', 'Partenaires')
@section('page-subtitle')
    <span class="v2-breadcrumb">Configurations <span>&rsaquo;</span> <strong>Partenaires</strong></span>
@endsection

@section('content')
    @if(CanPermission('agents_voir_longlet_agent'))
        <div class="row" id="container">
            <div class="col-md-12">
                @if(CanPermission('agents_ajouter_un_agent'))
                    <div class="text-end mb-4">
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#customer-edit_add-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter un partenaire
                        </a>
                    </div>

                    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendModuleForm" action="{{ route('panel.autorisations.partenaires.store') }}" method="POST">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Ajouter un partenaire</p>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="v2-modal-body">
                                    <div style="margin-bottom:16px;">
                                        <label class="v2-modal-label">Nom <code>*</code></label>
                                        <input type="text" class="v2-modal-input" name="name" required>
                                    </div>

                                    <div>
                                        <label class="v2-modal-label">Pourcentage <code>*</code></label>
                                        <input type="text" class="v2-modal-input" name="percent" value="50" min="0" max="100" required>
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
                                <th>Partenaire</th>
                                <th>Pourcentage</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr class="v2-table-loading">
                                    <td colspan="4"><span class="v2-spinner"></span> Chargement des données...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="v2-modal-header v2-modal-header--primary">
                            <span class="v2-modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </span>
                            <p class="v2-modal-header__title" id="updateModalLabel">Modifier un partenaire</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:auto;">&times;</button>
                        </div>
                        <form action="" method="POST" class="sendModuleUpdateForm">
                            @csrf
                            <div class="v2-modal-body updateModalBody"></div>
                            <div class="v2-modal-footer">
                                <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="v2-btn v2-btn--primary">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif 




@endsection


@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/partenaires.js') }}"></script>
@endpush
