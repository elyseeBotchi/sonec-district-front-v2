<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-layout-style="" data-layout-position="fixed" data-topbar="light">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="{{ env('APP_AUTHOR_NAME') }} | BENI Messan">
    <meta name="generator" content="">
    <link rel="icon" type="image/gif" href="{{ asset('images/logo_barreau.png') }}"/>
    <title>{{ env('APP_NAME') }} {{ date('Y') }}</title>

    <!-- plugin css -->
    <link href="{{ asset('backoffice/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('backoffice/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('backoffice/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%; /* Ensure the container takes up the full height of the cell */
        }
        .cell-padding {
            padding-left: 8px;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 80%;
        }
        body {
            font-size: x-small !important;
        }
        table {
            border: 1px solid silver;
        }
        ul li{
        margin-bottom: 10px !important;
        }

        /* CSS pour centrer le contenu */
        .center {
            text-align: center;
        }

        /* Styles spécifiques au QR Code si nécessaire */
        .qr-code {
            width: 150px;
        }

        /* Style pour le conteneur en bas de la page */
        .bottom-content {
            position: absolute;
            bottom: -10px; /* Ajustez la valeur selon vos besoins */
            width: 100%;
            text-align: center;
            font-size: xx-small;
        }
         .qrcode {
             float: right;
             position: relative;
             top: -10px;
             margin-left: 10px; /* Ajout de marge pour éviter le débordement */
         }
    </style>
    <style>
        body {
            font-size: 16px !important; /* Augmenter la taille globale du texte */
            line-height: 1.3; /* Améliorer la lisibilité */
        }
        table {
            font-size: 22px; /* Taille de texte dans les tables */
        }
        h4 {
            font-size: 20px; /* Augmenter les titres */
            text-transform: uppercase;
        }
        td, th {
            font-size: 15px; /* Taille des cellules de tableau */
        }
        .bottom-content {
            font-size: 15px; /* Ajuster le texte en bas de page */
        }
        code {
            font-size: 14px; /* Augmenter la taille des notes */
        }

        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            margin-top:5px;
            position: relative;
            z-index: 2; /* Devant le filigrane */
        }

        .header img {
            width: 50px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            position: relative;
            text-align: center;
            top: -5px;
            z-index: 2;
        }
    </style>
    
</head>

<body>

    <div style="
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    z-index: -1500; 
    opacity: 0.1; 
    pointer-events: none; 
    font-size: 80px; 
    color: #e8a7a7; 
">
    <!-- En haut à gauche -->
    <div style="
        position: absolute; 
        top: 120px; 
        left: 20px; 
        transform: rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

    <!-- Au centre -->
    <div style="
        position: absolute; 
        top: 50%; 
        left: 50%; 
        transform: translate(-50%, -50%) rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

    <!-- En bas à droite -->
    <div style="
        position: absolute; 
        bottom: 120px; 
        right: 20px; 
        transform: rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>
</div>
@isset($open)
    <div class="col-md-11">

        <div class="header">
            <img src="{{ public_path('template/assets/images/logo.png') }}" alt="Logo Gauche" style="float: left; width: 75px; height: 75px;margin-left:19px;">
            <img src="{{ public_path('backoffice/armoirie.jpg') }}" alt="Logo Droite" style="float: right; width: 85px; height: 85px;margin-right:10px;">
        </div>
    
        <!-- Titre -->
        <div class="title">
            <h4>District Autonome d´Abidjan</h4> 
            <h4 style="position :relative;top:-30px !important;">Direction Générale des services financiers</h4>
             <h4  style="position :relative;top:-57px !important;">Direction du recouvrement</h4> 
        </div>



        <div style="background-color: #0a9e2a;color: white;padding: 0.5px">
            <h4 class="text-center m-4 text-white uppercase" style="font-size: 15px; text-align:center; text-transform: uppercase !important; ">
                 FICHE DE COTATION - {{ $user['nom_du_proprietaire'] ?? '' }}
            </h4>
        </div>
        <br>

        <table class="" style="width: 100%;border: inherit;border:none;">
           
            <tr>
                <td style="font-size: meduim;" >
                    Abidjan le {{ date_create($user['updated_at'])->format('d-m-Y H:i:s') ?? '' }}
                </td>
                <td style="font-size: meduim"></td>
            </tr> 
            <tr>
                <td style="font-size: meduim"  >
                    COTATION N° : {{ $user['reference'] ?? '' }}
                </td>
                <td style="font-size: meduim"></td>
            </tr>
           
        </table> 
        
      
        <br>
        {{-- A REVOIR POUR LE SCRIPT D'ASSIGNATION --}}
        <table class="bg-white" style="width: 100%;font-size: meduim">
            <thead>
            <tr>
                <th  class="cell-padding" colspan="3" style="background-color: silver">
                    INFORMATIONS RELATIVES A LA COTATION
                </th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Référence
                    </td>
                    <td>
                        : <strong> {{ $user['reference'] ?? '' }} </strong>
                    </td>
                    <td rowspan="6">
                        <center style="position:relative;top:-48px;">
                            <img src="{{ public_path($svgFilePath) }}" alt="" width="120px" class="qrcode">
                        </center>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Nom de l'entreprise
                    </td>
                    <td>
                        : <strong> {{ $user['nom_du_proprietaire'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                       Compte contribuable
                    </td>
                    <td>
                        : <strong> {{ $user['contribuable'] ?? '' }} </strong>
                    </td>
                </tr>



                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Nombre de voiture à déclarer
                    </td>
                    <td>
                        : <strong> {{ $user['nombre_vehicule'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Contact téléphonique
                    </td>
                    <td>
                        : <strong> {{ $user['telephone'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Statut
                    </td>
                    <td>
                        : <strong> {!! getStatusBadge($user['status']) !!} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Date de soumission
                    </td>
                    <td>
                        : <strong> {{ date_create($user['updated_at'])->format('d-m-Y H:i:s') ?? '' }} </strong>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

@endisset

<div class="bottom-content">
    <span> Généré le {{ date('d-m-Y') }} à {{ date('H:i:s') }} </span>
    <br>
    <footer style="position:relative;bottom:0px;">
            <center>
                <?php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); ?>
                <img width="250px" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($quick_reference , $generator::TYPE_CODE_39))}}" />
                
            </center>
        Copyright © {{ date('Y') }} | {{ env('APP_NAME') }}. Tous Droits Réservés
    </footer>
</div>
<!-- JAVASCRIPT -->
@stack('footer-script')

<!-- App js -->


</body>
</html>
