@extends('layout.adminApp')

@section('content')
<div class="card-group">
    @if(CanPermission('activites_voir_les_scannes_journaliers'))   
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="penalite_journalier"> <i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pénalités journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></span>
                </div>
            </div>
        </div>
    </div>
@endif 

@if(CanPermission('activites_voir_le_nombre_de_scanne_mensuel'))   
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium" >
                            <span id="scanne_journalier"> <i class="fa fa-spinner fa-spin"></i> </span>
                        </h2>
                {{--                         <span class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">-18.33%</span>--}}                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Scanne journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                </div>
            </div>
        </div>
    </div>
@endif


@if(CanPermission('activites_voir_les_penalites_mensuel'))   
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                        <span id="penalite_mensuel"><i class="fa fa-spinner fa-spin"></i> </span>
                    </h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Penalité mensuel
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></span>
                </div>
            </div>
        </div>
    </div>

    @endif

    @if(CanPermission('activites_voir_le_nombre_de_scanne_mensuel'))   

    <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium" id="scanne_mensuel"> <i class="fa fa-spinner fa-spin"></i> </h2>
                    </div>

                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">
                        Scanne mensuel
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                </div>
            </div>
        </div>
    </div>

    @endif

</div>

<div class="row col-md-12">
    

    @if(CanPermission('activites_voir_lhistorique_des_controles'))   
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <h4 class="card-title mb-0">HISTORIQUE DES CONTROLES </h4>
                        
                    </div> 
                    <br>
                    <div class="ml-auto">
                        <div class="hide js-show" style="display: block;">
                            <div class="card-custom mb-4">
                                <div class="card-header-custom">
                                    <span id="languageSelectLabel" style="font-size:x-small;">
                                        Filtres
                                    </span>
                                </div>

                                <form class="searchData" action="{{ route('panel.autorisations.activity.agents.search') }}" method="POST">
                                    @csrf
                                    <div class="card-body-custom">
                                        <div class="row col-md-12 billing-section">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Agent</label>
                                                <select class="form-control" name="agent" id="agent">
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Date début </label>
                                                <input type="date" value="{{ date('Y-m-01') }}" class="form-control" id="dateBegin" name="dateBegin" required />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">Date fin</label>
                                                <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="dateEnd" name="dateEnd" required />
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="form-label">Résultat </label>
                                                <select class="form-control" name="status">
                                                    <option value="">Tous</option>
                                                    <option value="success">Valide</option>
                                                    <option value="expire">Expiré</option>
                                                    <option value="error">Incorrect</option>
                                                </select>
                                            </div>
                                        
                                            <div class="form-group col-md-1">
                                                <label class="form-label">&nbsp; &nbsp; &nbsp; </label>
                                                <button type="submit" class="btn btn-icon waves-effect waves-light material-shadow-none btn-outline-primary" title="Rechercher" >
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="pl-4 mb-5">
                        <table class="table table-stripped" id="datatable-custom">
                            <thead>
                                <tr>
                                    <td>Agent</td>
                                    <td>Date</td>
                                    <td>QR Code</td>
                                    <td>Bien</td>
                                    <td>Résultat</td>
                                    {{-- <td>Localisation</td> --}}

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
            </div>
        </div>
    @endif 


</div>

@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/activities.js') }}"></script> {{-- --}}
@endpush

@endsection
