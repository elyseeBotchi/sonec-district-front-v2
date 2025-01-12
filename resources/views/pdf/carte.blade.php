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
            font-size:16px;
            margin: 0;
            padding: 0;
        }

        .card-container_old {
            width: 120mm; /* Augmenté pour agrandir la zone */
            height: 70mm; /* Augmenté pour agrandir la zone */
            border: 2px solid #000;
            border-radius: 15px;
            background-color: #fff;
            margin: 50px auto;
            padding: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden; /* Assure que le filigrane ne dépasse pas */
        }

        .card-container {
            width: 178mm; /* Largeur pour un papier A5 */
            height: 120mm; /* Hauteur pour un papier A5 */
            border: 2px solid #000;
            border-radius: 15px;
            background-color: #fff;
            margin: 10px auto;
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
            top: -85px;
            z-index: 2;
        }

        .info-section {
            font-size: 16px;
            line-height: 1.0;
            margin-bottom: 10px;
            margin-top: -40px;
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
            width: 100px; /* Réduit pour s'adapter à la zone */
            height: 100px;
            position: absolute;
            z-index: 2;
        }

        .qr-top-left {
            top: -5px;
            left: 10px;
        }

        .qr-top-right {
            top: -5px;
            right: 10px;
        }

        .qr-bottom-left {
            bottom: -5px;
            left: 10px;
        }

        .qr-bottom-right {
            bottom: -5px;
            right: 10px;
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
    <div style="
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    z-index: -1500; 
    opacity: 0.1; 
    pointer-events: none; 
    font-size: 65px; 
    color: #e8a7a7; 
">
    <!-- En haut à gauche -->
    <div style="
        position: absolute; 
        top: 165px; 
        left: 10px; 
        transform: rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

    <!-- Au centre -->
    <div style="
        position: absolute; 
        top: 30%; 
        left: 50%; 
        transform: translate(-50%, -50%) rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>

    <!-- En bas à droite -->
    <div style="
          position: absolute; 
        top: 36%; 
        left: 75%; 
        transform: translate(-50%, -50%) rotate(-45deg); 
        white-space: nowrap;
    ">
        {{ $watermark ?? "DIS|TSA- " . date('y') }}
    </div>
</div>

    <!-- QR Codes dans les coins -->
    <img src="{{ public_path($svgFilePath) }}" alt="" class="qr-code qr-top-left">
    <img src="{{ public_path($svgFilePath) }}" alt="" class="qr-code qr-top-right">
    <img src="{{ public_path($svgFilePath) }}" alt="" class="qr-code qr-bottom-left">
    <img src="{{ public_path($svgFilePath) }}" alt="" class="qr-code qr-bottom-right">

    <br>
    <br>
    <br>
    <br>
    <!-- En-tête -->
    <div class="header">
        <img src="{{ public_path('template/assets/images/logo.png') }}" alt="Logo Gauche" style="float: left; width: 55px; height: 55px;margin-left:19px;">
        <img src="{{ public_path('backoffice/armoirie.jpg') }}" alt="Logo Droite" style="float: right; width: 65px; height: 65px;margin-right:10px;">
    </div>

    <!-- Titre -->
    <div class="title">
        CARTE DE STATIONNEMENT {{ date('Y') }}
        <center style="font-size: 20px;">DISTRICT D'ABIDJAN</center>
        <center style="font-size: 20px;margin-top:10px; color:green;"> <b>{{ $paiement['reference'] ?? '' }}</b> </center>
    </div>

    <!-- Informations -->
    <div class="info-section">
        <table style="width: 100%; font-size: meduim; border-collapse: collapse;">
            <thead>
                <tr>
                    <td  style="border: 1px solid black;background-color:orange;"><b>NOM PROPRIETAIRE</b> </td>
                    <td  style="border: 1px solid black;background-color:orange;"> <b>TYPE DE VEHICULE</b> </td>
                    <td  style="border: 1px solid black;background-color:orange;"> <b>MONTANT PAYER</b> </td>
                    <td  style="border: 1px solid black;background-color:orange;"> <b>N° CARTE GRISE</b> </td>
                    <td  style="border: 1px solid black;background-color:orange;"> <b>N° IMMATRICULATION</b> </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td  style="border: 1px solid black;"> <b>{{ $pay_element['nom_du_proprietaire'] ?? '' }}</b> </td>
                    <td  style="border: 1px solid black;"> <b>{{ $service ?? '' }}</b>  </td>
                    <td  style="border: 1px solid black;"> <b> {{ $paiement['amount'] ?? '' }}</b></td>
                    <td  style="border: 1px solid black;"> <b> {{ $pay_element['numero_de_la_carte_grise'] ?? '' }}</b></td>
                    <td  style="border: 1px solid black;"><b>{{ $pay_element['numero_dimmatriculation'] ?? '' }}</b> </td>
                </tr>
            </tbody>
        </table>
        <p style="font-size:xx-small;"><strong>Date de paiement :</strong>  {{ date_create($paiement['updated_at'])->format('d-m-Y h:i:s') ?? '' }}
        <br>
        <br><strong>Date de validité :</strong> {{ date_create($paiement['date_fin'])->format('d-m-Y') }}

        {{-- calculateEndDate(date('01-01-Y', strtotime($paiement['updated_at'] ?? '')), $facturation['periodicity'] ?? '') --}}</p>

        <center style="position: relative;bottom: -175px;">
            <?php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); ?>
            <img width="350px" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($quick_reference , $generator::TYPE_CODE_39))}}" />
            
        </center>
    </div>
</div>
</body>
</html>
