@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Agents</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item active">Agents</li>
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
                            <i class="fas fa-plus"></i> Ajouter un agent
                        </a>
                    </div>

                    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendCreateForm" action="{{ route('panel.autorisations.agents.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="mb-0 text-uppercase">Ajouter un agent</h5>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="civility-man">
                                                            <input type="radio" class="btn-outline-secondary" name="civility" id="civility-man" value="m" checked> Monsieur
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="civility-woman">
                                                            <input type="radio" class="btn-outline-secondary" name="civility" id="civility-woman" value="mme"> Madame
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Nom <code>*</code></label>
                                                        <input type="text" class="form-control" name="firstname">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Prénom(s) <code>*</code></label>
                                                        <input type="text" class="form-control" name="lastname">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">E-mail <code>*</code></label>
                                                        <input type="text" class="form-control" name="email">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Téléphone <code>*</code></label>
                                                        <input type="text" class="form-control" name="phone">
                                                    </div>
                                                </div>
                                            </div>   

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Matricule <code>*</code></label>
                                                        <input type="text" class="form-control" name="matricule">
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
                            <td class="text-white">Nom & Prénom(s)</td>
                            <td class="text-white">E-mail</td>
                            <td class="text-white">Téléphone</td>
                            <td class="text-white">Matricule</td>
                            <td class="text-white">Statut</td>
                            <td class="text-white"></td>
                        </tr>
                        </thead>
                        <tbody class="render-html">
                            <tr>
                                <td colspan="6"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif 
@endsection

@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/agents.js') }}"></script>
@endpush
