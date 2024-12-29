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
</head>

<body>
<div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.1; font-size: 80px; color: #e8a7a7; z-index: -1500; white-space: nowrap;">
    {{ $watermark ?? 'TAXE DE DISTRICT '.date('Y') }}
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
               <td width="180px" style="font-size: xx-small">
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




        <table class="" style="width: 100%;border: inherit;border:none;">
           
            <tr>
                <td style="font-size: xx-small;" >
                    Abidjan le {{ date_create($user['updated_at'])->format('d-m-Y H:i:s') ?? '' }}
                </td>
                <td style="font-size: xx-small"></td>
            </tr> 
            <tr>
                <td style="font-size: xx-small"  >
                    REÇU N° : {{ $user['reference'] ?? '' }}
                </td>
                <td style="font-size: xx-small"></td>
            </tr>
           
        </table>



        <br>

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
        <table class="bg-white" style="width: 100%; font-size: xx-small; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">
                        NATURE DES TAXES
                    </th>
                    <th style="border: 1px solid black;">
                        QUANTITE
                    </th>
                    <th style="border: 1px solid black;">
                        TAUX OU TARIF
                    </th>  
                    <th style="border: 1px solid black;">
                        MONTANT
                    </th>             
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black;">
                        {{ $service ?? '' }}
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        1
                    </td>
                    <td style="border: 1px solid black; text-align: right;">
                        {{ $user['amount'] ?? '' }} 
                    </td>  
                    <td style="border: 1px solid black; text-align: right;">
                        {{ $user['amount'] ?? '' }}
                    </td>             
                </tr>
                <tr>
                    <td style="border: 1px solid black;">
                        MONTANT TOTAL
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        1
                    </td>
                    <td style="border: 1px solid black;">
                        <!-- Empty cell -->
                    </td>  
                    <td style="border: 1px solid black; text-align: right;">
                        {{ $user['amount'] ?? '' }} F CFA
                    </td>             
                </tr>
                <tr>
                    <td colspan="4">
                        Arrêté le présent reçu  à la somme de : {{ enlettre($user['amount'] ?? '') }} Francs CFA
                    </td>
                </tr>
            </tbody>
        </table>
        
        
      
        <br>
        {{-- A REVOIR POUR LE SCRIPT D'ASSIGNATION --}}
        <table class="bg-white" style="width: 100%;font-size: xx-small">
            <thead>
            <tr>
                <th  class="cell-padding" colspan="3" style="background-color: silver">
                    INFORMATIONS RELATIVES AU PAIEMENT
                </th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Référence de paiement
                    </td>
                    <td>
                        : <strong> {{ $user['reference'] ?? '' }} </strong>
                    </td>
                    <td rowspan="6">
                        <center style="position:relative;top:-25px;">
                            <img src="{{ public_path($svgFilePath) }}" alt="" width="65px" class="qrcode">
                        </center>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Numero de paiement
                    </td>
                    <td>
                        : <strong> {{ $user['telephone'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Id Transaction
                    </td>
                    <td>
                        : <strong> {{ $user['transaction_id'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Montant payé
                    </td>
                    <td>
                        : <strong> {{ $user['amount'] ?? '' }} </strong>
                    </td>
                </tr>

                <tr>
                    <td class="cell-padding"  style="width: 250px !important">
                        Date de paiement
                    </td>
                    <td>
                        : <strong> {{ date_create($user['updated_at'])->format('d-m-Y H:i:s') ?? '' }} </strong>
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
        @isset($barcodeData)
            <center>
                <img src="{{ asset($barcodeData) }}" alt="" >
            </center>
        @endisset
            <br>
        Copyright © {{ date('Y') }} | {{ env('APP_NAME') }}. Tous Droits Réservés
    </footer>
</div>
<!-- JAVASCRIPT -->
@stack('footer-script')

<!-- App js -->


</body>
</html>
