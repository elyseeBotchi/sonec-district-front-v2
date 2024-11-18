@isset($lock)
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-layout-style="" data-layout-position="fixed" data-topbar="light">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="{{ env('APP_AUTHOR_NAME') }}">
    <meta name="generator" content="">
    <link rel="icon" type="image/gif" href="{{ asset('images/logo_barreau.png') }}"/>
    <title>{{ env('APP_NAME') }} {{ date('Y') }}</title>

    <!-- CSS -->
    <link href="{{ asset('backoffice/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* Conteneur de la carte */
        .parking-card {
            width: 300px;
            height: 450px;
            border: 2px solid #333;
            padding: 15px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            font-family: 'Arial', sans-serif;
        }

        /* En-tête de la carte avec le logo */
        .parking-card .header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .parking-card .header img {
            width: 60px;
            margin-right: 10px;
        }

        .parking-card .header h2 {
            font-size: 16px;
            text-transform: uppercase;
            color: #333;
        }

        /* Informations du propriétaire et du véhicule */
        .parking-card .details {
            margin-bottom: 20px;
        }

        .parking-card .details p {
            font-size: 14px;
            color: #555;
        }

        .parking-card .details strong {
            font-size: 14px;
            color: #000;
        }

        /* Conteneur du QR Code */
        .parking-card .qr-code {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .parking-card .qr-code img {
            width: 90px;
        }

        /* Pied de page avec la date d'émission */
        .parking-card .footer {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            text-align: center;
        }

        .parking-card .footer span {
            display: block;
        }

    </style>
</head>

<body>
<div class="parking-card">
    <!-- En-tête avec logo et titre -->
    <center class="header">
        <h2 class="text-center">Carte de Stationnement</h2>
        <img src="{{ public_path('template/assets/images/logo.png') }}" class="float-right" alt="Logo">
    </center>

    <!-- Détails du propriétaire et véhicule -->
    <div class="details">
        <table class="table">
            <tr>
                <td>Nom :</td>
                <td>{{ $paiement['name'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Immatriculation :</td>
                <td>{{ $paiement['vehicle_plate'] ?? '' }}</td>
            </tr>
            <tr>
                <td>Durée :</td>
                <td>{{ $paiement['parking_duration'] ?? '' }} jours</td>
            </tr>
            <tr>
                <td>Date de début :</td>
                <td>{{ date('d-m-Y', strtotime($paiement['start_date'] ?? '')) }}</td>
            </tr>
            <tr>
                <td>Date de fin :</td>
                <td>{{ date('d-m-Y', strtotime($paiement['end_date'] ?? '')) }}</td>
            </tr>
            <tr>
                <td>Montant payé :</td>
                <td>{{ $paiement['amount'] ?? '' }} F CFA</td>
            </tr>
        </table>
    </div>

    <!-- QR Code au bas de la carte -->
    <div class="qr-code">
        <img src="{{ public_path($svgFilePath) }}" alt="QR Code">
        <p>Scan pour vérifier</p>
    </div>

    <!-- Pied de page avec les détails de génération -->
    <div class="footer">
        <span>Généré le {{ date('d-m-Y') }}</span>
    </div>
</div>
</body>
</html>

@endisset <!doctype html>
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
            width: 85mm;
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
        {{ env('APP_NAME').' '.date('Y') ?? 'T-CONNECT '.date('Y') }}
    </div>
    
    <!-- Header with logos at the extreme left and right -->
    <div class="header">
        <img src="{{ public_path('template/assets/images/logo.png') }}" alt="Logo Droit" style="float: left; width: 50px; height: 50px;">
        <img src="{{ public_path('backoffice/armoirie.jpg') }}" alt="Logo Gauche" style="float: right; width: 50px; height: 50px;">
    </div>

    <div class="title">
        CARTE DE STATIONNEMENT
        <center style="font-size: 10px;">DISTRICT D'ABIDJAN</span>
    </div>
<br>

<div class="info-section">
        <table>
            @isset($entete)
                @forelse($entete as $key => $value)
                @if($key <= 2)
                        <tr>
                            <th>{{ $value['name'] ?? '' }}  </th>
                            <td>@isset($pay_element[$value['slug']]) {{ $pay_element[$value['slug']] ?? '' }} @endisset </td>
                        </tr>    
                     @endif 
                @empty
                @endforelse
        
                <tr>
                    <th>Durée </th>
                    <td>{{ $facturation['quantity'] ?? '' }} {{ translatePeriodicity($facturation['periodicity'] ?? '') }}</td>
                </tr>
            @endisset 
            <tr>
                <th>Date de début </th>
                <td>
                    {{ date('01-01-Y', strtotime($paiement['updated_at'] ?? '')) }}
                </td>
            </tr>
            <tr>
                <th>Date de fin </th>
                <td>
                    {{  calculateEndDate(date('01-01-Y', strtotime($paiement['updated_at'] ?? '')) ?? '', $facturation['periodicity'] ?? '') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- QR Code at the bottom right -->
    <img src="{{ public_path($svgFilePath) }}" alt="QR Code" class="qr-code">

</div>
</body>
</html>




