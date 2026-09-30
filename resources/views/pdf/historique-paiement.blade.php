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
        .cell-padding {
            padding-left: 8px;
        }
        body {
            font-size: x-small !important;
        }
        table {
            border: 1px solid silver;
        }

        .center {
            text-align: center;
        }

        .bottom-content {
            position: absolute;
            bottom: -10px;
            width: 100%;
            text-align: center;
            font-size: xx-small;
        }
    </style>
    <style>
        body {
            font-size: 16px !important;
            line-height: 1.3;
        }
        table {
            font-size: 16px;
        }
        h4 {
            font-size: 20px;
            text-transform: uppercase;
        }
        td, th {
            font-size: 13px;
        }
        .bottom-content {
            font-size: 15px;
        }
        code {
            font-size: 14px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            margin-top:5px;
            position: relative;
            z-index: 2;
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
    <div style="
        position: absolute;
        top: 120px;
        left: 20px;
        transform: rotate(-45deg);
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

    <div style="
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

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
                 HISTORIQUE DE PAIEMENT - {{ $entity['name'] ?? '' }}
            </h4>
        </div>
        <br>

        <table class="" style="width: 100%;border: inherit;border:none;">
            <tr>
                <td style="font-size: meduim;">
                    Abidjan le {{ date('d-m-Y H:i:s') }}
                </td>
                <td style="font-size: meduim"></td>
            </tr>
            <tr>
                <td style="font-size: meduim">
                    NOMBRE DE PAIEMENTS : {{ is_array($factures) ? count($factures) : 0 }}
                </td>
                <td style="font-size: meduim"></td>
            </tr>
        </table>

        <br>

        @isset($entete)
            <table class="bg-white" style="width: 100%;font-size: meduim;border: inherit">
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
                                    @if($value['slug'] =="numero_de_la_carte_grise")
                                    Numéro de la carte grise
                                    @elseif($value['slug'] =="numero_dimmatriculation")
                                    Numéro d'immatriculation
                                    @elseif($value['slug'] =="telephone")
                                    Téléphone
                                    @else
                                        {{ $value['name'] ?? '' }}
                                    @endif
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

        <table class="bg-white" style="width: 100%;font-size: meduim">
            <thead>
                <tr>
                    <th  class="cell-padding" colspan="6" style="background-color: silver">
                        HISTORIQUE DES PAIEMENTS
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">DATE</th>
                    <th style="border: 1px solid black;">RÉFÉRENCE</th>
                    <th style="border: 1px solid black;">TRANSACTION</th>
                    <th style="border: 1px solid black;">MONTANT</th>
                    <th style="border: 1px solid black;">OPÉRATEUR</th>
                    <th style="border: 1px solid black;">STATUT</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($factures as $facture)
                    <tr>
                        <td style="border: 1px solid black;">{{ isset($facture['updated_at']) ? date_create($facture['updated_at'])->format('d-m-Y H:i:s') : 'N/A' }}</td>
                        <td style="border: 1px solid black;">{{ $facture['reference'] ?? 'N/A' }}</td>
                        <td style="border: 1px solid black;">{{ $facture['transaction_id'] ?? 'N/A' }}</td>
                        <td style="border: 1px solid black; text-align: center;">{{ isset($facture['amount']) ? $facture['amount'].' FCFA' : 'N/A' }}</td>
                        <td style="border: 1px solid black;">{{ $facture['operateur_uuid'] ?? 'N/A' }}</td>
                        <td style="border: 1px solid black; text-align: center;">
                            @php
                                $stateLabels = [
                                    'pending' => 'En attente',
                                    'progress' => 'En cours',
                                    'success' => 'Réussi',
                                    'enable' => 'Actif',
                                    'desable' => 'Inactif',
                                    'fail' => 'Rejeté',
                                    'error' => 'Annulé',
                                    '1' => 'Actif',
                                    '0' => 'Inactif',
                                ];
                                $state = $facture['state'] ?? '';
                            @endphp
                            {{ $stateLabels[$state] ?? $state }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="border: 1px solid black; text-align: center;">Aucun paiement retrouvé.</td>
                    </tr>
                @endforelse
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
                <img width="250px" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($pay_element['numero_dimmatriculation'] ?? $entity['uuid'] ?? date('YmdHis'), $generator::TYPE_CODE_39))}}" />
            </center>
        Copyright © {{ date('Y') }} | {{ env('APP_NAME') }}. Tous Droits Réservés
    </footer>
</div>
<!-- JAVASCRIPT -->
@stack('footer-script')

</body>
</html>
