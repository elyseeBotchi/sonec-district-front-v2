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
            bottom: -20px; /* Ajustez la valeur selon vos besoins */
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
            font-size: 28px; /* Augmenter les titres */
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
    </style>
</head>

<body>
    @isset($lock)
<div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.1; font-size: 80px; color: #e8a7a7; z-index: -1500; white-space: nowrap;">
    {{ $watermark ?? "DIS|TSA- ".date('y') }}
</div>
@endisset
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
        <table style="width: 100%;border: inherit">
           <tr>
               <td>
                    <img src="{{ public_path('template/assets/images/logo.png') }}" width="100px">
                </td>
               <td>
               
               </td>
               <td width="180px" style="font-size: medium">
                   <center>
                       TAXE DU DISTRICT
                       <br>
                       ---------------------------
                       <br>
                       Direction des finances                      
                   </center>
               </td>
           </tr>
        </table>
        <br>


        <div style="background-color: #0a9e2a;color: white;padding: 0.5px">
            <h4 class="text-center m-4 text-white uppercase" style="font-size: 15px; text-align:center; text-transform: uppercase !important; ">
                 FICHE DE RENDEZ-VOUS - {{ $entity['name'] ?? '' }}
            </h4>
        </div>
        <br>

        <table class="" style="width: 100%;border: inherit;border:none;">
           
            <tr>
                <td style="font-size: medium;" >
                    Abidjan le {{ date_create($user['updated_at'])->format('d-m-Y H:i:s') ?? '' }}
                </td>
                <td style="font-size: medium"></td>
            </tr> 
            <tr>
                <td style="font-size: medium"  >
                    REÇU N° : {{ $user['reference'] ?? '' }}
                </td>
                <td style="font-size: medium"></td>
            </tr>
           
        </table>



        <br>
    <!--
        @isset($entete)
            <table class="bg-white" style="width: 100%;font-size: xx-small;border: inherit">
                <thead>
                    <tr>
                        <th  class="cell-padding" colspan="2" style="background-color: silver">
                            INFORMATIONS RELATIVES AU VEHICULE
                        </th>
                    </tr>
                <tbody>
                    @foreach ($entete as $value)
                        @isset($value['slug'])
                        <tr>
                                <td class="cell-padding">
                                    {{ $value['name'] ?? '' }}
                                </td>
                                <td style="text-align: right;">
                                    @isset($pay_element[$value['slug']]) 
                                        {{ $pay_element[$value['slug']]  ?? '' }}
                                     @endisset 
                                </td>
                            </tr>
                        @endisset
                    @endforeach
                </tbody>
            </table>
        <br>
        @endisset 
    -->
        <table class="bg-white" style="width: 100%; font-size: xx-small; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">
                        Nom Propretaire
                    </th>
                    <th style="border: 1px solid black;">
                        Montant payé
                    </th>
                    <th style="border: 1px solid black;">
                        Lieu du rendez-vous
                    </th>  
                    <th style="border: 1px solid black;">
                        Date du rendez-vous
                    </th>  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black;">
                        {{ $pay_element['nom_du_proprietaire'] ?? '' }}
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        <b>{{ $user['amount'] ?? '' }} </b>
                    </td>
                    <td style="border: 1px solid black; text-align: left;">
                        {{ $pay_element['lieu_rendez_vous'] ?? '' }}
                    </td>  
                    <td style="border: 1px solid black; text-align: right;">
                        <b>{{ date_create($pay_element['date_rdv'])->format('d-m-Y') ?? '' }}</b>
                    </td>             
                </tr>

                
            </tbody>
        </table>

        <br> <br> <br>
      <code style="text-align: justify"> <strong>NB:</strong> Ce reçu de paiement ne tient pas lieu de quittance de stationnement. Veuillez vous rendre au district pour la validation et le retrait de votre quittance de stationnement, muni de ce reçu et des pièces afférentes au véhicule. </code>
    </div>

@endisset

<div class="bottom-content">
    <span> Généré le {{ date('d-m-Y') }} à {{ date('H:i:s') }} </span>
    <br>
    <footer>
        <center>
            <?php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); ?>
            <img width="200px" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($quick_reference , $generator::TYPE_CODE_39))}}" />
            
        </center>
            <br>
        Copyright © {{ date('Y') }} | {{ env('APP_NAME') }}. Tous Droits Réservés
    </footer>
</div>
<!-- JAVASCRIPT -->
@stack('footer-script')

<!-- App js -->


</body>
</html>
