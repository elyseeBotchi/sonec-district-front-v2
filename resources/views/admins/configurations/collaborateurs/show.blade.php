@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Collaborateurs</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Collaborateurs</li>
                    <li class="breadcrumb-item active">Détail</li>
                </ol>
            </div>

        </div>
    </div>

    <div class="row" id="container">
        <div class="col-sm-12">
             <div class="row">
                 <div class="col-sm-12">
                     <div class="card">
                         <div class="card-body py-0">
                             <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                                 <li class="nav-item">
                                     <a class="nav-link active" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                                        aria-selected="true">
                                         <i class="ti ti-user me-2"></i> Détail du compte
                                     </a>
                                 </li>

                              {{--    <li class="nav-item">
                                     <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                                        aria-selected="true">
                                         <i class="fas fa-history me-2"></i> Historique de connexion
                                     </a>
                                 </li>

                                 <li class="nav-item">
                                     <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4" role="tab"
                                        aria-selected="true">
                                         <i class="ti ti-settings me-2"></i> Settings
                                     </a>
                                 </li>--}}
                             </ul>
                         </div>
                     </div>

                     <div class="tab-content">
                         <div class="tab-pane show active" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                             <div class="row">
                                 <div class="col-lg-6">
                                     <div class="card">
                                         <div class="card-header">
                                             <h5>Information personnelle</h5>
                                         </div>
                                         <form class="sendForm" method="POST" action="{{ route('panel.autorisations.collaborateurs.update', $user->uuid) }}" enctype="multipart/form-data">
                                            @csrf
                                             <div class="card-body">
                                                 <div class="row">
                                                     <div class="col-sm-12 text-center mb-3 d-none">
                                                         <div class="user-upload wid-75">
                                                             <img src="{{ env('apiUrl').'/storage/'. $user->avatar }}" alt="img" class="img-fluid">
                                                         </div>
                                                     </div>
                                                     <div class="col-sm-12 d-none">
                                                        <div class="form-group">
                                                            <label class="form-label">Avatar</label>
                                                            <input type="file" class="form-control" name="avatar">
                                                        </div>
                                                    </div>
                                                     <div class="col-sm-6">
                                                         <input type="hidden" name="uuid" value="{{ $user->uuid ?? '' }}" required >
                                                         <div class="form-group">
                                                             <label class="form-label">Nom</label>
                                                             <input type="text" class="form-control" name="firstname" value="{{ $user->firstname ?? '' }}" required>
                                                         </div>
                                                     </div>
                                                     <div class="col-sm-6">
                                                         <div class="form-group">
                                                             <label class="form-label">Prénoms</label>
                                                             <input type="text" class="form-control" name="lastname" value="{{ $user->lastname ?? '' }}" required>
                                                         </div>
                                                     </div>
                                                     <div class="col-sm-6">
                                                         <div class="form-group">
                                                             <label class="form-label">Email</label>
                                                             <input type="email" class="form-control" value="{{ $user->email ?? '' }}" readonly>
                                                         </div>
                                                     </div>
                                                     <div class="col-sm-6">
                                                         <div class="form-group">
                                                             <label class="form-label">Telephone</label>
                                                             <input type="tel" class="form-control" name="phone" value="{{ $user->phone ?? '' }}" required>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="card-footer">
                                                 <button type="submit" class="btn btn-rounded btn-outline-primary">Sauvegarder</button>
                                             </div>
                                         </form>
                                    </div>
                                 </div>
                                 <div class="col-lg-6">
                                     <div class="card">
                                         <div class="card-header">
                                             <h5>Roles</h5>
                                         </div>
                                         <div class="card-body">
                                            <table class="table table-striped" id="roles-table">
                                                <thead>
                                                    <tr class="bg-primary text-uppercase">
                                                        <td class="text-white">Rôle</td>
                                                        {{--<td class="text-white">Fonction</td>--}}
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                         </div>
                                     </div>

                                     @if(CanPermission('collaborateurs_assigner_un_role_a_un_collaborateur'))
                                        <div class="card">
                                            <form action="{{ route('panel.autorisations.collaborateurs.add_role', $user->uuid) }}" method="POST" class="sendCreateForm">
                                                @csrf
                                                <div class="card-header">
                                                    <h5>Ajouter un role</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                                                    <select name="role_uuid" id="role-uuid" class="form-control">

                                                                    </select>
                                                            </div>
                                                        </div>
                                                        @isset($lock)
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="heading-uuid" class="form-label">Définir une fonction ? <span class="text-danger">*</span></label>
                                                                    <select name="heading_uuid" id="heading-uuid" class="form-control"></select>
                                                                </div>
                                                            </div>
                                                        @endisset

                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-rounded btn-outline-primary">Sauvegarder</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif 


                                 </div>
                             </div>
                         </div>

                         <div class="tab-pane" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
                             <div class="row">
                                 <div class="col-12">
                                     <div class="card">
                                         <div class="card-header">
                                             <h5>Historique de connexion</h5>
                                         </div>
                                         <div class="card-body">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-3">
                             <div class="row">
                                 <div class="col-12">
                                     <div class="card">
                                         <div class="card-header">
                                             <h5>Setting</h5>
                                         </div>
                                         <div class="card-body">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                     </div>
                 </div>
             </div>
        </div>
    </div>
@endsection
@push('footer-script')
 <script src="{{ asset('backoffice/js/users.show.js') }}"></script>

 <script>
    var userUuid = @Json($user->uuid);
  </script>
@endpush
