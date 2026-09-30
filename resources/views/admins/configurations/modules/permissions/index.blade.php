@extends('layout.adminApp')

@section('page-title')
    Permission {{ $module->name ?? '' }}
@endsection
@section('page-subtitle')
    <span class="v2-breadcrumb">Configuration <span>&rsaquo;</span> <a href="{{ route("panel.autorisations.modules.index") }}">Module {{ $module->name ?? '' }}</a> <span>&rsaquo;</span> <strong>Permissions</strong></span>
@endsection

@section('content')
        <div class="row" id="container">
            <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#createModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter une permission
                        </a>
                    </div>
                    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white text-uppercase" id="createModalLabel">Ajouter une permission de {{ $module->name ?? '' }}</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('panel.autorisations.permissions.store', $module->uuid) }}" method="POST" class="sendModuleForm">
                                    @csrf
                                    <input type="hidden" name="uuid" value="{{ $module->uuid }}" required>
                                    <div class="modal-body">
                                        <div class="form-group mb-4">
                                            <label for="name" class="form-label">Permission</label>
                                            <input type="text" name="name" class="form-control" autofocus>
                                        </div>
                                        <div class="form-group d-none">
                                            <div class="row text-center">
                                                <div class="col">
                                                    <label for="input-write">
                                                        <input type="radio" id="input-write" name="category" value="write" checked> Ecriture
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <label for="input-read">
                                                        <input type="radio" id="input-read" name="category" value="read"> Lecture
                                                    </label>
                                                </div>
                                            </div>
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
                                    <h5 class="modal-title text-white text-uppercase" id="updateModalLabel">Modifier une permission de {{ $module->name ?? '' }}</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="" method="POST" class="sendModuleUpdateForm">
                                    @csrf
                                    <div class="modal-body updateModalBody"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-shadow" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary  btn-shadow">Sauvegarder</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="v2-card">
                        <div class="v2-table-wrap">
                            <table class="v2-table" id="datatable-custom" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Permissions</th>
                                    <th style="width: 100px">Action</th>
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
        </div>
@endsection

@push('footer-script')
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  <script src="{{ asset('backoffice/js/permissions.js') }}"></script>
  <script>
    var moduleUuid = @Json($module->uuid);
  </script>
@endpush
