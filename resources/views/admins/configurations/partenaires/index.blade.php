@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Partenaires</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item active">Partenaires</li>
                </ol>
            </div>
        </div>
    </div>

    @if(CanPermission('agents_voir_longlet_agent'))
        <div class="row" id="container">
            <div class="col-md-12">
                @if(CanPermission('agents_ajouter_un_agent'))
                    <div class="text-end mb-4">
                        <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#customer-edit_add-modal">
                            <i class="fas fa-plus"></i> Ajouter un partenaire
                        </a>
                    </div>

                    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendModuleForm" action="{{ route('panel.autorisations.partenaires.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="mb-0 text-uppercase">Ajouter un partenaire</h5>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="modal-body"> 

                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Nom <code>*</code></label>
                                                        <input type="text" class="form-control" name="name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Pourcentage <code>*</code></label>
                                                        <input type="text" class="form-control" name="percent" value="50" min="0" max="100" required>
                                                    </div>
                                                </div>
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
                @endif 

                <div class="pt-5">
                    <table class="table" id="datatable-custom">
                        <thead>
                        <tr class="bg-primary text-uppercase">
                            <td class="text-white">Partenaire</td>
                            <td class="text-white">Pourcentage</td>
                            <td class="text-white">Statut</td>
                            <td class="text-white"></td>
                        </tr>
                        </thead>
                        <tbody class="render-html">
                            <tr>
                                <td colspan="4"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="updateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-uppercase text-white" id="updateModalLabel">Modifier un partenaire</h5>
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
        </div>
    @endif 




@endsection


@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/partenaires.js') }}"></script>
@endpush
