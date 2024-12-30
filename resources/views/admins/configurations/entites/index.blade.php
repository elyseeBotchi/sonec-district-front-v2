@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Entités</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Configurations</li>
                    <li class="breadcrumb-item active">Entités</li>
                </ol>
            </div>
        </div>
    </div>

    @if(CanPermission('entites_configurer_une_entite'))
        <div class="row" id="container">
            <div class="col-md-12">
                @if(CanPermission('entites_ajouter_une_entite'))
                    <div class="text-end mb-4">
                        <a href="#" class="btn btn-rounded btn-outline-primary float-right" data-toggle="modal" data-target="#customer-edit_add-modal">
                            <i class="fas fa-plus"></i> Ajouter une entité
                        </a>
                    </div>

                    <div class="modal fade" id="customer-edit_add-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.entite.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="mb-0 text-uppercase">Ajouter une entité </h5>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Dénomination <code>*</code></label>
                                                <input type="text" class="form-control btn-outline-secondary" name="name" required />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Libellé public <code>*</code></label>
                                                <input type="text" class="form-control btn-outline-secondary" name="front_name" required />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div id="columns">
                                                <div class="column row">
                                                    <div class="col-md-4">
                                                        <label for="column_name">Libellé du Champ</label>
                                                        <input type="text" class="form-control btn-outline-secondary" name="columns[0][name]" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="column_type">Type DB</label>
                                                        <select class="form-control" name="columns[0][type]" required>
                                                            <option value="string">String</option>
                                                            <option value="longtext">Long texte</option>
                                                            <option value="text">Text</option>
                                                            <option value="integer">Integer</option>
                                                            <option value="bigInteger">Big Integer</option>
                                                            <option value="smallInteger">Small Integer</option>
                                                            <option value="tinyInteger">Tiny Integer</option>
                                                            <option value="boolean">Boolean</option>
                                                            <option value="decimal">Decimal</option>
                                                            <option value="float">Float</option>
                                                            <option value="double">Double</option>
                                                            <option value="date">Date</option>
                                                            <option value="datetime">Datetime</option>
                                                            <option value="timestamp">Timestamp</option>
                                                            <option value="time">Time</option>
                                                            <option value="year">Year</option>
                                                            <option value="binary">Binary</option>
                                                            <option value="uuid">UUID</option>
                                                            <option value="json">JSON</option>
                                                            <option value="jsonb">JSONB</option>
                                                            <option value="enum">Enum</option>
                                                            <option value="set">Set</option>
                                                            <option value="geometry">Geometry</option>
                                                            <option value="point">Point</option>
                                                            <option value="linestring">LineString</option>
                                                            <option value="polygon">Polygon</option>
                                                            <option value="multipoint">MultiPoint</option>
                                                            <option value="multilinestring">MultiLineString</option>
                                                            <option value="multipolygon">MultiPolygon</option>
                                                            <option value="geometrycollection">GeometryCollection</option>
                                                            <option value="ipAddress">IP Address</option>
                                                            <option value="macAddress">MAC Address</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <label for="column_type">Type Input</label>
                                                        <select class="form-control" name="columns[0][type_input]" required>
                                                            <option value="text">String</option>
                                                            <option value="email">Email</option>
                                                            <option value="number">Chiffre</option>
                                                            <option value="tel">Téléphone</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="column_name">&nbsp; &nbsp; &nbsp; </label>
                                                        <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-danger" onclick="removeColumn(this)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                
                                
                                        <div class="col-md-12">
                                            <br>
                                            <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-warning" onclick="addColumn()">Ajouter un champ</button>
                                        </div>

                                        <div class="col-md-12">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Description </label>
                                                <textarea name="description" class="form-control btn-outline-secondary" id="" cols="30" rows="10"></textarea>
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
                
                @if(CanPermission('entites_modifier_une_entite'))
                    <div class="modal fade" id="updateElement-modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <form class="modal-content sendEntiteForm" action="{{ route('panel.autorisations.entite.update') }}" method="POST">
                                @csrf
                                <input type="hidden" id="uuid" name="uuid" required />
                                <div class="modal-header">
                                    <h5 class="mb-0 text-uppercase">Modifier l'entité <span id="target_update_name"></span> </h5>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-dismiss="modal">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Dénomination <code>*</code></label>
                                                <input type="text" class="form-control btn-outline-secondary" id="libelle" name="name" required />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Dénomination <code>*</code></label>
                                                <input type="text" class="form-control btn-outline-secondary" id="front_name" name="front_name" required />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <br>
                                            <div class="form-group">
                                                <label class="form-label">Description </label>
                                                <textarea name="description" class="form-control btn-outline-secondary" id="description" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-shadow closeUpModal" data-dismiss="modal">Fermer</button>
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
                            <td class="text-white">Dénomination</td>
                            <td class="text-white">Version</td>
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
        </div>
    @endif 
@endsection

@push('footer-script')
    <script>
        let columnCount = 1;
    
        function addColumn() {
            const columns = document.getElementById('columns');
            const newColumn = document.createElement('div');
            newColumn.className = 'column row';
            newColumn.innerHTML = `
                <div class="col-md-4">
                    <label for="column_name">Nom du Champ</label>
                    <input type="text" class="form-control btn-outline-secondary" name="columns[${columnCount}][name]" required>
                </div>
                <div class="col-md-3">
                    <label for="column_type">Type</label>
                    <select class="form-control" name="columns[${columnCount}][type]" required>
                         <option value="string">String</option>
                        <option value="longtext">Long texte</option>
                        <option value="text">Text</option>
                        <option value="integer">Integer</option>
                        <option value="bigInteger">Big Integer</option>
                        <option value="smallInteger">Small Integer</option>
                        <option value="tinyInteger">Tiny Integer</option>
                        <option value="boolean">Boolean</option>
                        <option value="decimal">Decimal</option>
                        <option value="float">Float</option>
                        <option value="double">Double</option>
                        <option value="date">Date</option>
                        <option value="datetime">Datetime</option>
                        <option value="timestamp">Timestamp</option>
                        <option value="time">Time</option>
                        <option value="year">Year</option>
                        <option value="binary">Binary</option>
                        <option value="uuid">UUID</option>
                        <option value="json">JSON</option>
                        <option value="jsonb">JSONB</option>
                        <option value="enum">Enum</option>
                        <option value="set">Set</option>
                        <option value="geometry">Geometry</option>
                        <option value="point">Point</option>
                        <option value="linestring">LineString</option>
                        <option value="polygon">Polygon</option>
                        <option value="multipoint">MultiPoint</option>
                        <option value="multilinestring">MultiLineString</option>
                        <option value="multipolygon">MultiPolygon</option>
                        <option value="geometrycollection">GeometryCollection</option>
                        <option value="ipAddress">IP Address</option>
                        <option value="macAddress">MAC Address</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="column_type">Type Input</label>
                    <select class="form-control" name="columns[${columnCount}][type_input]" required>
                        <option value="text">String</option>
                        <option value="email">Email</option>
                        <option value="number">Chiffre</option>
                        <option value="tel">Telephone</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="column_name">&nbsp; &nbsp; &nbsp; </label>
                    <button type="button" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-danger" onclick="removeColumn(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
            columns.appendChild(newColumn);
            columnCount++;
        }
    
        function removeColumn(button) {
            button.closest('.column').remove();
        }
    </script>
    
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/entite.js') }}"></script>
@endpush
