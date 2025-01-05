@extends('layout.customerApp')

@section('content')

    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Mon compte</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Sécurité</li>
                    <li class="breadcrumb-item active">Mon compte</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row" id="container">
         <div class="col-sm-12">

             <div class="tab-content">
                 <div class="tab-pane show active" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                     <div class="row">

                         <div class="col-lg-6">
                             <div class="card">
                                 <div class="card-header">
                                     <h5>Information personnelle</h5>
                                 </div>
                                 <form class="sendForm" method="POST" action="{{ route('customer.securite.compte.update.data') }}" enctype="multipart/form-data">
                                     @csrf
                                     <div class="card-body">
                                         <div class="row">

                                             {{--###########################--}}
                                             <div class="col-sm-12 text-center mb-3">
                                                 <div class="user-upload wid-75">
                                                     <img
                                                         @isset($collaborator['avatar'])
                                                             src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.$collaborator['uuid'].'/'.$collaborator['avatar']) }}"
                                                         @else
                                                             src="{{ asset($collaborator['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                                                         @endisset
                                                         alt="img" class="rounded-circle img-fluid" style="width: 100px" id="avatarPreview">

                                                     <input type="file" id="uploadfile" name="avatar" class="d-none" accept="image/*">
                                                 </div>
                                                 <label for="uploadfile" class="img-avtar-upload">
                                                     <i class="fa fa-camera f-24 mb-1"></i>
                                                     <span>Modifier l'avatar</span>
                                                 </label>
                                             </div>

                                             {{--###########################--}}
                                             <div class="col-sm-12">
                                                 <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required >
                                                 <div class="form-group">
                                                     <label class="form-label">Civilité</label>

                                                     <select name="civility" id="" class="form-control">
                                                        <option value="m" @isset($collaborator['civility']) @if($collaborator['civility'] =="m") selected @endif @endisset >Monsieur</option>
                                                        <option value="mme" @isset($collaborator['civility']) @if($collaborator['civility'] =="mme") selected @endif @endisset >Madame</option>
                                                        <option value="mlle" @isset($collaborator['civility']) @if($collaborator['civility'] =="mlle") selected @endif @endisset >Demoiselle</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required >
                                                 <div class="form-group">
                                                     <label class="form-label">Nom</label>
                                                     <input type="text" class="form-control" name="firstname" value="{{ $collaborator['firstname'] ?? '' }}" required>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Prénoms</label>
                                                     <input type="text" class="form-control" name="lastname" value="{{ $collaborator['lastname'] ?? '' }}" required>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Email</label>
                                                     <input type="email" class="form-control" name="email" value="{{ $collaborator['email'] ?? '' }}" readonly />
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Telephone</label>
                                                     <input type="tel" class="form-control" name="phone" value="{{ $collaborator['phone'] ?? '' }}" required>
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
                                     <h5>Modification du compte</h5>
                                 </div>

                                    <form class="sendForm" method="POST" action="{{ route('customer.securite.compte.update.password')}}" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Ancien Mot de passe</label>
                                                        <input type="password" class="form-control" name="old_password" min="6" placeholder="Ancien mot de passe" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Nouveau mot de passe</label>
                                                        <input type="password" class="form-control" name="password" min="6" placeholder="Nouveau mot de passe" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Confirmation du mot de passe</label>
                                                        <input type="password" name="confirm" class="form-control" min="6" required />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-rounded btn-outline-primary">Modifier</button>
                                        </div>
                                    </form>


                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
     </div>


@endsection
@push('footer-script')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
            let id;
            $('.btn-danger').on('click', function(e){
                id = $(this).data('id');
            });
            $('#btn-delete').on('click', function(e){
                $('#delete-'+id).submit();
            });
        } );
    </script>
@endpush
