@extends('layout.adminApp')

@section('page-title', 'Modules')
@section('page-subtitle')
    <span class="v2-breadcrumb">Autorisations <span>&rsaquo;</span> <strong>Modules</strong></span>
@endsection

@section('content')
        <div class="row" id="container">
            @if(CanPermission('module_voir_longlet_module'))
                <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#createModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter un module
                        </a>
                    </div>

                    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form action="{{ route('panel.autorisations.modules.store') }}" method="POST" class="modal-content sendModuleForm">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Ajouter un module</p>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="v2-modal-body">
                                    <label for="name" class="v2-modal-label">Module <code>*</code></label>
                                    <input type="text" name="name" class="v2-modal-input" autofocus>
                                </div>
                                <div class="v2-modal-footer">
                                    <button type="button" class="v2-modal-footer__close closeModal" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="v2-btn v2-btn--primary">Sauvegarder</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form action="" method="POST" class="modal-content sendModuleUpdateForm">
                                @csrf
                                <div class="v2-modal-header v2-modal-header--primary">
                                    <span class="v2-modal-header__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </span>
                                    <p class="v2-modal-header__title">Modifier un module</p>
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
                                    <th>Module</th>
                                    <th style="width: 25%">Action</th>
                                </tr>
                                </thead>
                                <tbody class="render-html">
                                    <tr class="v2-table-loading">
                                        <td colspan="2"><span class="v2-spinner"></span> Chargement des données...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

@endsection

@if(CanPermission('module_voir_longlet_module'))
    @push('footer-script')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('backoffice/js/modules.js') }}"></script>
    @endpush
@endif
