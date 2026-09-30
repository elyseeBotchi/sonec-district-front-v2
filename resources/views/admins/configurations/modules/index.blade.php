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
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-uppercase text-white" id="createModalLabel">Ajouter un module</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('panel.autorisations.modules.store') }}" method="POST" class="sendModuleForm">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Module</label>
                                            <input type="text" name="name" class="form-control" autofocus>
                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-shadow" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary btn-shadow">Sauvegarder</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-uppercase text-white" id="updateModalLabel">Modifier un module</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="" method="POST" class="sendModuleUpdateForm">
                                    @csrf
                                    <div class="modal-body updateModalBody"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-shadow" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary  btn-shadow">Sauvegarder</button>
                                    </div>
                                </form>
                            </div>
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
