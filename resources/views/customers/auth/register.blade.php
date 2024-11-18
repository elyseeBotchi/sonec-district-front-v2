@extends('layout.registerApp') 
@section('content')
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative" >

    <div class="col-md-5 bg-white" style="width: 700px !important; font-family: 'Cambria Math', sans-serif; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div class="p-3">
                <a href="{{ route('welcome.index') }}" class="text-center">
                    <center>
                        <img src="{{ url('template/assets/images/logo.png') }}" width="70px" alt="">
                    </center>
                </a>

                <h2 class="mt-3 text-center"> Créer un compte de <span id="TaxeEntity" ></span> </h2>
                <p class="" style="font-family :'Montserrat', Helvetica, Arial, serif"></p>
                
                <form class="mt-4 sendForm" action="{{ route('customer.register.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entity_uuid" value="{{ $entity_uuid ?? '' }}" required>
                        <div class="col-md-12 row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="uname">Email</label>
                                    <input class="form-control" name="email" id="uname" type="email"
                                           placeholder="Email" required/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Mot de passe</label>
                                    <input class="form-control" name="password" id="pwd" type="password" placeholder="Mot de passe" required />
                                </div>
                            </div>
    
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Confirmer votre mot de passe</label>
                                    <input class="form-control" name="confirm" id="confirm_pwd" type="password" placeholder="Confirmer le mot de passe" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Civilité</label>
                                    <select name="civility_uuid" class="form-control" id="">
                                        <option value="m">Monsieur</option>
                                        <option value="Mme">Madame</option>
                                        <option value="Mlle">Mademoiselle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Nom</label>
                                    <input class="form-control" name="firstname" id="firstname" type="text" placeholder="Nom de l'utilisateur" required />
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Prénoms</label>
                                    <input class="form-control" name="lastname" id="lastname" type="text" placeholder="Prénoms de l'utilisateur" required />
                                </div>
                            </div>
                        </div>
                    
                        <div id="form-container" class="col-md-12 row"> <span class="fa fa-spinner fa-spin"></span> </div>

                        <center>
                             <div class="col-lg-3 text-center">
                                <button type="submit" class="btn btn-block btn-outline-primary">Valider</button>
                            </div>
                        </center>
                       
                    
                </form>            
        </div>
    </div>
</div>

@endsection



@push('footer-script')
    <script>
        
        var Entity_uuid = @Json($entity_uuid);
        /* ########################################################## */
    </script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/front/register.js') }}"></script>
@endpush