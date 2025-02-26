@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Systèmes</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Autorisations</li>
                    <li class="breadcrumb-item active">Systèmes</li>
                </ol>
            </div>
        </div>
    </div>
        <div class="row" id="container">
            @if(CanPermission('module_voir_longlet_module'))
                <div class="col-md-12">
                    <div class="text-end mb-3">
                        <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#createModal">
                            <i class="fa fa-plus"></i> Ajouter une règle
                        </a>
                    </div>

                    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-uppercase text-white" id="createModalLabel">Ajouter une règle</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('panel.autorisations.systemes.store') }}" method="POST" class="sendModuleForm">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Date de début</label>
                                            <input type="date" name="start"value="{{ date('Y-m-d') }}" class="form-control" autofocus>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="form-label">Date de fin</label>
                                            <input type="date" name="end" class="form-control" />
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="form-label">Ratio (%)</label>
                                            <input type="number" name="rate" class="form-control" max="100" required />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input type="number" name="total_cumul" class="form-control" required />
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
                                    <h5 class="modal-title text-uppercase text-white" id="updateModalLabel">Modifier une règle</h5>
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

                    <div class="pt-5">
                        <table class="table table-striped" id="datatable-custom">
                            <thead>
                            <tr class="bg-primary text-uppercase">
                                <td class="text-white">Date de début</td>
                                <td class="text-white">Date de fin</td>
                                <td class="text-white">ratio</td>
                                <td class="text-white">Nombre cumul</td>
                                <td  class="text-white" style="width: 25% !important"></td>
                            </tr>
                            </thead>
                            <tbody class="render-html">
                                <tr>
                                    <td colspan="5"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

@endsection

@if(CanPermission('module_voir_longlet_module'))
    @push('footer-script')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('backoffice/js/systemes.js') }}"></script>
    @endpush
@endif
