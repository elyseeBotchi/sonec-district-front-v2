@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Roles</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Configuration</li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>

        </div>
    </div>
    <div class="row" id="container">
        <div class="col-md-12">
            <div class="text-end mb-3">
                  <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#createModal">
                    <i class="fa fa-plus"></i> Ajouter un rôle
                  </a>
            </div>

            <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-uppercase text-white" id="createModalLabel">Ajouter un rôle</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('panel.autorisations.roles.store') }}" method="POST" class="sendModuleForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Rôle</label>
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
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-uppercase text-white" id="updateModalLabel">Modifier un rôle</h5>
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

            <div  class="pt-5">
                <table class="table bg-white" id="datatable-custom" style="width: 100%;">
                    <thead>
                    <tr class="bg-primary text-uppercase">
                        <td class="text-white">Rôle</td>
                        <td style="width: 24% !important;"></td>
                    </tr>
                    </thead>
                    <tbody class="render-html">
                    <tr>
                        <td colspan="2"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('footer-script')
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('backoffice/js/roles.js') }}"></script>
@endpush
