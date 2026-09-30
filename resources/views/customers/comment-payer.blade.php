@extends('layout.customerApp')

@section('content')

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Comment payer vos taxes</h4>

            <div class="step-grid">
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
                            <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la carte de stationnement.</li>
                        </ul>
                    </div>
                </div>

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
                            <li>Se rendre au district muni du reçu de paiement imprimé et des pièces afférentes au véhicule pour la validation et le retrait de la carte de stationnement.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
