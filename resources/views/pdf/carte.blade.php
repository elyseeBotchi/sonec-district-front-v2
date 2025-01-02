<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="{{ env('APP_AUTHOR_NAME') }}">
    <title>Carte de Stationnement</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .card-container {
            width: 95mm;
            height: 55mm;
            border: 2px solid #000;
            border-radius: 15px;
            background-color: #fff;
            margin: 50px auto;
            padding: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden; /* Assure que le filigrane ne dépasse pas */
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            font-size: 25px;
            color: #e8a7a7;
            white-space: nowrap;
            z-index: 1; /* Derrière tout le contenu de la carte */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            position: relative;
            z-index: 2; /* Devant le filigrane */
        }

        .header img {
            width: 50px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            position: relative;
            z-index: 2;
        }

        .info-section {
            font-size: 10px;
            line-height: 1.0;
            margin-bottom: 10px;
            word-wrap: break-word;
            position: relative;
            z-index: 2;
        }

        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-section th, .info-section td {
            text-align: left;
            padding: 4px;
            font-size: 10px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 2;
        }

        .footer {
            font-size: 10px;
            text-align: center;
            position: absolute;
            bottom: 5px;
            width: 100%;
            color: #666;
            z-index: 2;
        }
    </style>
</head>

<body>

    
<div class="card-container">
    <!-- Filigrane -->
    <div class="watermark">
        {{ env('APP_NAME').' '.date('Y') ?? 'TAXE DE DISTRICT '.date('Y') }}
    </div>
    
    <!-- Header with logos at the extreme left and right -->
    <div class="header">
        <img src="{{ public_path($svgFilePath) }}" alt="QR Code Droite" class="qr-code" style="float: left; width: 50px; height: 50px;">
        <img src="{{ public_path($svgFilePath2) }}" alt="QR Code Gauche" class="qr-code" style="float: right; width: 50px; height: 50px;">

        <img src="{{ public_path('template/assets/images/logo.png') }}" alt="Logo Droit" style="float: left; width: 50px; height: 50px;">
        <img src="{{ public_path('backoffice/armoirie.jpg') }}" alt="Logo Gauche" style="float: right; width: 50px; height: 50px;">
    </div>

    <div class="title">
        QUITTANCE DE STATIONNEMENT
        <center style="font-size: 10px;">DISTRICT D'ABIDJAN</span>
    </div>
<br>

<div class="info-section"><br/><br/><br/>
        <table width="100% !important;">
            <thead>
                <tr>
                    <td>Nom du proprietaire </td>
                    <td>Carte grise</td>
                    <td>Immatriculation </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table><br/><br/><br/>
        <div><p align="left"><strong>Date de paiement : </strong></p></div>
        <div><p align="left"><strong>Date de validité : {{  calculateEndDate(date('01-01-Y', strtotime($paiement['updated_at'] ?? '')) ?? '', $facturation['periodicity'] ?? '') }}</strong></p></div>
    </div>

    <!-- QR Code at the bottom right -->
    <img src="{{ public_path($svgFilePath) }}" alt="QR Code Droite" class="qr-code" style="float: left; width: 50px; height: 50px;">
    <img src="{{ public_path($svgFilePath2) }}" alt="QR Code Gauche" class="qr-code" style="float: right; width: 50px; height: 50px;">

</div>
</body>
</html>




