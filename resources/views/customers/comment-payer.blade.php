@extends('layout.customerApp')

@section('content')
   
<style>

    .step {
        margin-bottom: 20px;
    }
    .step h2 {
        /* font-size: 18px; */
        color: #06a3da;
        margin-bottom: 10px;
    }
    .step ul {
        list-style: none;
        padding-left: 0;
    }
    .step ul li {
        margin-bottom: 10px;
        padding-left: 25px;
        position: relative;
        font-size: 20px;
    }
    .step ul li::before {
        content: '✔';
        position: absolute;
        left: 0;
        color: #28a745;
        font-weight: bold;
    }
    .note {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-left: 4px solid #06a3da;
        font-style: italic;
    }
    .note strong {
        color: #007BFF;
    }
    .step h2 {
        font-size: 18px;
        color: #06a3da;
        margin-bottom: 10px;
    }
    h1, h2, .fw-bold {
    font-weight: 800 !important;
    }

    h2, .h2 {
        font-size: calc(1.325rem + .9vw);
    }
    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
  margin-top: 0;
  margin-bottom: .5rem;
  font-family: "Nunito",sans-serif;
  font-weight: 500;
  line-height: 1.2;
  color: #091E3E;}

</style>

<br>
<br>
<div class="step">
    <h2>Méthode 1 : Paiement rapide</h2>
    <div>
        <h3>Étape 1</h3>
        <ul>
            <li>Se rendre sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>
            <li>Consulter la liste des taxes</li>
            <li>Renseigner les informations personnelles du propriétaire du véhicule et les informations afférentes au véhicule</li>
            <li>Procéder au paiement avec l'un des opérateurs Mobile Money (Orange, MTN ou Wave)</li>
            <li>Imprimer votre reçu de paiement</li>
        </ul>
    </div>
    <div>
        <h3>Étape 2</h3>
        <ul>
            <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la quittance de stationnement.</li>
        </ul>
    </div>
</div>
<br>
<br>

<div class="step">
    <h2>Méthode 2 : Création de compte et paiement</h2>
    <div>
        <h3>Étape 1</h3>
        <ul>
            <li>Se rendre sur le site : <a href="https://district-online.ci/" target="_blank">https://district-online.ci/</a></li>                                
            <li>Creer votre compte </li>
            <li>Se connecter à son espace requérant</li>
            <li>Renseigner les informations afférentes aux véhicules </li>
            <li>Procéder au paiement avec l'un des opérateurs Mobile Money (Orange, MTN ou Wave)</li>
            <li>Imprimer votre reçu de paiement</li>

        </ul>
    </div>
    <div>
        <h3>Étape 2</h3>
        <ul>
            <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la quittance de stationnement.</li>
        </ul>
    </div>
</div>


@endsection
