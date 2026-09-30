@extends('layout.adminApp')

@section('page-title')
    <span class="entity_name"><i class="fa fa-spinner fa-spin"></i></span>
@endsection
@section('page-subtitle')
    <span class="v2-breadcrumb">Configurations <span>&rsaquo;</span> Entités <span>&rsaquo;</span> <strong class="entity_name"><i class="fa fa-spinner fa-spin"></i></strong></span>
@endsection

@section('content')
    @if(CanPermission('gabaris_voir_longlet_gabaris'))
    <div class="row" id="container">
        <div class="col-md-12">
            <div class="text-end mb-4">
 
                @if(CanPermission('gabaris_charger_un_gabari'))
                <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#customer-edit_add-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Uploader le gabari
                </a>
                @endif

                @if(CanPermission('gabaris_telecharger_le_model_de_gabari'))
                 <a href="{{ route('panel.autorisations.entite.gabari.model',['uuid' => $Entity_uuid ?? '' ]) }}" class="v2-btn v2-btn--ghost">Télécharer le model </a>
                @endif
            </div>

            @if(CanPermission('gabaris_charger_un_gabari'))
            <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <form class="modal-content sendCreateForm" action="{{ route('panel.autorisations.entite.gabari.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $Entity_uuid ?? '' }}" name="entity_uuid" required />
                        <div class="v2-modal-header v2-modal-header--primary">
                            <span class="v2-modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </span>
                            <p class="v2-modal-header__title">Charger un gabari</p>
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal" style="margin-left:auto;">
                                <i class="ti ti-x f-20"></i>
                            </a>
                        </div>
                        <div class="v2-modal-body">
                            <div style="margin-bottom: 16px;">
                                <label class="v2-modal-label">Fichier <code>*</code></label>
                                <div class="v2-file-input">
                                    <span class="v2-file-input__name" id="gabari-file-name">Aucun fichier choisi</span>
                                    <label for="gabari-file-input" class="v2-file-input__btn">Choisir un fichier</label>
                                </div>
                                <input type="file" id="gabari-file-input" name="gabari" required style="display:none;" />
                            </div>

                            <div>
                                <label class="v2-modal-label">Description</label>
                                <textarea name="description" class="v2-modal-input" rows="5" placeholder="Ajoutez une description ici..."></textarea>
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
                            <th>Fichier</th>
                            <th>Date de soumission</th>
                            <th>Soumis par</th>
                            <th>Date de validation</th>
                            <th>Valider par</th>
                            <th>Statut</th>
                            <th width="150px">Action</th>
                        </tr>
                        </thead>
                        <tbody class="render-html">
                            <tr class="v2-table-loading">
                                <td colspan="7"><span class="v2-spinner"></span> Chargement des données...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var fileInput = document.getElementById('gabari-file-input');
                var fileName = document.getElementById('gabari-file-name');
                if (fileInput && fileName) {
                    fileInput.addEventListener('change', function () {
                        fileName.textContent = fileInput.files[0] ? fileInput.files[0].name : 'Aucun fichier choisi';
                    });
                }
            });
        </script>
        @endpush
@endif