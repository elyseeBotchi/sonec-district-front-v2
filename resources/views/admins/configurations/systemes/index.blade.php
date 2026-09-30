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
                        <a href="#" class="v2-btn v2-btn--primary" data-toggle="modal" data-target="#createModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Ajouter une règle
                        </a>
                    </div>

                    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-uppercase text-white" id="createModalLabel">Ajouter une règle</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('panel.autorisations.systemes.store') }}" method="POST" class="sendModuleForm">
                                    @csrf
                                    <div class="modal-body row">
                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Date de début</label>
                                            <input type="date" name="start"value="{{ date('Y-m-d') }}" class="form-control" autofocus>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Date de fin</label>
                                            <input type="date" name="end" class="form-control" />
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Ratio (%)</label>
                                            <input type="number" name="rate" class="form-control" max="100" required />
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input type="number" name="total_cumul" class="form-control" required />
                                        </div>  

                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Type de véhicule</label>
                                            <select name="facturation_uuid" class="form-control" id="rubrique"></select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="name" class="form-label">Montant</label>
                                            <input type="text" name="montant_pay" id="montant_pay" class="form-control" readonly />
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
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-uppercase text-white" id="updateModalLabel">Modifier une règle</h5>
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
                            <table class="v2-table" id="datatable-custom">
                                <thead>
                                <tr>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Ratio</th>
                                    <th>Nombre cumul</th>
                                    <th>Type de véhicule</th>
                                    <th>Statut</th>
                                    <th style="width: 25%"></th>
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
            @endif
        </div>

@endsection

@if(CanPermission('module_voir_longlet_module'))@endif
    @push('footer-script')
        <script>
            var Entity_uuid = @Json(Entities()[0]['uuid'] ?? '');
        </script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <script src="{{ asset('backoffice/js/systeme.js') }}"></script>
    @endpush

