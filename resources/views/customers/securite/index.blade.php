@extends('layout.customerApp')

@section('content')

    <div class="card profile-card">
        <div class="card-body">

            <div class="profile-avatar">
                <img
                    @isset($collaborator['avatar'])
                        src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.$collaborator['uuid'].'/'.$collaborator['avatar']) }}"
                    @else
                        src="{{ asset($collaborator['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                    @endisset
                    alt="img" class="rounded-circle img-fluid" id="avatarPreview">
                <label for="uploadfile" class="profile-avatar__edit">
                    <i class="fa fa-camera"></i>
                </label>
                <input type="file" id="uploadfile" name="avatar" class="d-none" accept="image/*">
            </div>

            <h4 class="profile-title">Mon profil</h4>
            <p class="profile-subtitle">Gérez vos informations personnelles et votre compte</p>

            <form class="sendForm" method="POST" action="{{ route('customer.securite.compte.update.data') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required>

                <div class="profile-grid">
                    <div class="form-group">
                        <label class="form-label">Civilité</label>
                        <select name="civility" class="form-control">
                            <option value="m" @isset($collaborator['civility']) @if($collaborator['civility'] =="m") selected @endif @endisset >Monsieur</option>
                            <option value="mme" @isset($collaborator['civility']) @if($collaborator['civility'] =="mme") selected @endif @endisset >Madame</option>
                            <option value="mlle" @isset($collaborator['civility']) @if($collaborator['civility'] =="mlle") selected @endif @endisset >Demoiselle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" name="email" value="{{ $collaborator['email'] ?? '' }}" readonly />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="firstname" value="{{ $collaborator['firstname'] ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" name="phone" value="{{ $collaborator['phone'] ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénoms</label>
                        <input type="text" class="form-control" name="lastname" value="{{ $collaborator['lastname'] ?? '' }}" required>
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Enregistrer</button>
                </div>
            </form>

            <div class="profile-divider"><span>Sécurité du compte</span></div>

            <form class="sendForm" method="POST" action="{{ route('customer.securite.compte.update.password')}}" enctype="multipart/form-data">
                <div class="profile-grid">
                    <div class="form-group">
                        <label class="form-label">Ancien mot de passe</label>
                        <input type="password" class="form-control" name="old_password" min="6" placeholder="Ancien mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" name="password" min="6" placeholder="Nouveau mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmation du mot de passe</label>
                        <input type="password" name="confirm" class="form-control" min="6" required />
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Modifier le mot de passe</button>
                </div>
            </form>

        </div>
    </div>

@endsection
