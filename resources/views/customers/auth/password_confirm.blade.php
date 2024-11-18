@extends('layout.authentificationApp')

@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" >

    <div class="col-md-3 bg-white" style="width: 700px !important; font-family: 'Cambria Math', sans-serif; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="p-3">
            <a href="{{ route('welcome.index') }}" class="text-center">
                <center>
                    <img src="{{ url('template/assets/images/logo.png') }}" width="70px" alt="">
                </center>
            </a>

            <h2 class="mt-3 text-center">Récupération</h2>
            <p class="" style="font-family :'Montserrat', Helvetica, Arial, serif">
                
            </p>
        
            <form class="mt-4 sendForm" action="{{ route('panel.password.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}" required>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="text-dark" for="uname">Mot de passe</label>
                            <input class="form-control" name="password" id="uname" type="password" placeholder="Mot de passe" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="text-dark" for="pwd">Confirmer le mot de passe</label>
                            <a href="{{ route('panel.login') }}" class="text-dark float-right" for="pwd">Se connecter ?</a>
                            <input class="form-control" name="confirm" id="pwd" type="password" placeholder="Confirmer le mot de passe" required>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-block btn-outline-primary">Valider</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
