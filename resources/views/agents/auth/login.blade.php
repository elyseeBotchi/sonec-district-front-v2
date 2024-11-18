@extends('layout.authentificationApp') 
@section('content')

<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" style="background: url({{ url('template/start/img/dis.jpg') }}) no-repeat center center; background-size: cover; width: 100vw; height: 100vh;">

    <div class="col-md-3 bg-white" style="width: 700px !important; font-family: 'Cambria Math', sans-serif; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="p-3">
                <a href="{{ route('welcome.index') }}" class="text-center">
                    <center>
                        <img src="{{ url('template/assets/images/logo.png') }}" width="70px" alt="">
                    </center>
                </a>

                <h2 class="mt-3 text-center">ESPACE CONTROLEUR</h2>
                <p class="text-center" style="font-family :'Montserrat', Helvetica, Arial, serif"></p>
         
                <form class="mt-4 sendForm" action="{{ route('controle.connexion') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="text-dark" for="uname">Matricule</label>
                                <input class="form-control" name="matricule" id="uname" type="text"
                                       placeholder="Matricule">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="text-dark" for="pwd">Mot de passe</label>
                                <a href="{{ route('panel.forget.password') }}" class="text-dark float-right" for="pwd">Oublié ?</a>
                                <input class="form-control" name="password" id="pwd" type="password" placeholder="Mot de passe">
                            </div>
                        </div>
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn btn-block btn-outline-primary">Connexion</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>



@endsection
