@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0"> 
                <span class="entity_name">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item">Entités</li>
                    <li class="breadcrumb-item active entity_name">
                        <i class="fa fa-spinner fa-spin"></i>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    @if(CanPermission('gabaris_voir_longlet_gabaris'))
    <div class="row" id="container">
        <div class="col-md-12">
            <div class="text-end mb-4">
 
                @if(CanPermission('gabaris_charger_un_gabari'))
                <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#customer-edit_add-modal">
                    <i class="fas fa-plus"></i> Uploader le gabari
                </a>
                @endif

                @if(CanPermission('gabaris_telecharger_le_model_de_gabari'))
                 <a href="{{ route('panel.autorisations.entite.gabari.model',['uuid' => $Entity_uuid ?? '' ]) }}" class="btn btn-rounded btn-outline-primary float-right mr-2">Télécharer le model </a>
                @endif
            </div>

            @if(CanPermission('gabaris_charger_un_gabari'))
            <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendCreateForm" action="{{ route('panel.autorisations.entite.gabari.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid" required />
                        <div class="modal-header">
                            <h5 class="mb-0 text-uppercase">Charger un gabari </h5>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                          
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Fichier <code>*</code></label>
                                        <input type="file" class="form-control" name="gabari" required />
                                    </div>
                                </div>
                          
                                <div class="col-md-12">
                                    <br>
                                    <div class="form-group">
                                        <label class="form-label">Description </label>
                                        <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-shadow closeModal" data-dismiss="modal">Fermer</button>
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
                        <td class="text-white">Fichier</td>
                        <td class="text-white">Date de soumission</td>
                        <td class="text-white">Soumis par</td>
                        <td class="text-white">Date de validation</td>
                        <td class="text-white">Valider par</td>
                        <td class="text-white">Statut</td>
                        <td class="text-white" width="150px";>Action</td>
                    </tr>
                    </thead>
                    <tbody class="render-html">
                        <tr>
                            <td colspan="7"> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    @endsection

    @if(CanPermission('gabaris_voir_longlet_gabaris'))
@push('footer-script')
    @isset($Entity_uuid)
        <script>
            var Entity_uuid = @Json($Entity_uuid ?? '');
        </script>
    @endisset
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('/backoffice/js/gabari.js') }}"></script> 
        @endpush
@endif