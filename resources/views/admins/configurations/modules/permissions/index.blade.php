@extends('layout.adminApp')

@section('content')
        <div class="row" id="container">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Permission {{ $module->name ?? '' }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">Configuration</li>
                            <li class="breadcrumb-item"><a href="{{ route("panel.autorisations.modules.index") }}">Module {{ $module->name ?? '' }}</a> </li>
                            <li class="breadcrumb-item active">Permissions</li>
                        </ol>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#createModal">
                            <i class="fa fa-plus"></i> Ajouter une permission
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

                    <div class="pt-5">
                        <table class="table" id="datatable-custom" style="width: 100%;">
                            <thead>
                            <tr class="bg-primary text-uppercase">
                                <td class="text-white">Permissions</td>
                                <td style="width: 100px"></td>
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
  <script src="http://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  <script src="{{ asset('backoffice/js/permissions.js') }}"></script>
  <script>
    var moduleUuid = @Json($module->uuid);
  </script>
@endpush
