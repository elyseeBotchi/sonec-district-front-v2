<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{

    public function index()
    {
        $url_path = "/landing/services/operateurs";

        $data = [
           // 'uuid' => $target
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('index', [
                    'operateurs'=>$responses['data'] ?? '',
                ]);
            }
            else{
                return view('index', [
                    'operateurs'=>$responses['data'] ?? '',
                ]);
            }
        }
        else{
            return view('index', [
                'operateurs'=>$responses['data'] ?? '',
            ]);
        }
    }

    
    public function index2()
    {
        $url_path = "/landing/services/operateurs";

        $data = [
           // 'uuid' => $target
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('index2', [
                    'operateurs'=>$responses['data'] ?? '',
                ]);
            }
            else{
                return view('index2', [
                    'operateurs'=>$responses['data'] ?? '',
                ]);
            }
        }
        else{
            return view('index2', [
                'operateurs'=>$responses['data'] ?? '',
            ]);
        }
    }

    public function quick_pay($name=null,$service)
    {
        $url_path = "/landing/services/operateurs";

        $data = [
            'entity_uuid' => $service
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // dd($service);
      //dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('quickPayForm', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? '',
                    'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                    'lieuRdv' => $responses['lieuRdv'] ?? '',
                    'limit' => $datas['limit'] ?? 5
                ]);
            }
            else{
                return view('quickPayForm', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? '',
                    'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                    'lieuRdv' => $responses['lieuRdv'] ?? '',
                    'limit' => $limit ?? 1

                ]);
            }
        }
        else{
            return view('quickPayForm', [
                'operateurs'=>$responses['data'] ?? '',
                'service_uuid' => $service ?? '',
                'service_name' => $name ?? '',
                'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                'lieuRdv' => $responses['lieuRdv'] ?? '',
                'limit' => $limit ?? 5

            ]);
        }
    }
    

    
    

    public function quick_liste($name=null,$service)
    {
        $url_path = "/landing/services/operateurs";

        $data = [
           // 'uuid' => $service
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('liste-taxes', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? ''
                ]);
            }
            else{
                return view('liste-taxes', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? ''

                ]);
            }
        }
        else{
            return view('liste-taxes', [
                'operateurs'=>$responses['data'] ?? '',
                'service_uuid' => $service ?? '',
                'service_name' => $name ?? ''

            ]);
        }
    }

    
    public function quick_acquitter($name=null,$service)
    {
        return view('acquitter-taxe', [
            'service_uuid' => $service ?? '',
            'service_name' => $name ?? ''
        ]);
    }
    
    public function about($name=null,$service)
    {
        return view('about', [
            'service_uuid' => $service ?? '',
            'service_name' => $name ?? ''
        ]);
    }

    public function about_send(Request $request)
    {
        if($request->motif ==""){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>'Veuillez renseigner le motif',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
        $url_path = "/landing/services/send/email";

        if($request->motif =="autre"){
            $motif = $request->autre_motif;
        }

        $data = [
            'entity_uuid' => $request->entity_uuid,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'motif' => $motif ?? $request->motif,
            'message' => $request->message,
        ];

       // dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       
        $services = Entities_Customer();
        //return response()->json($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']),
                    'message'=>$responses['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
            else{
                 $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$responses['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
            }
        }
        else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=> "ne erreur est survenu lors de l'envoi",
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }
    
    public function findOneConfig($uuid){

        $url_path = "/landing/services/rubrique/findOneConfig";

        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $uuid,
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($response);
        return response()->json($response);
    }

    public function findAllService($uuid)
    {
        $url_path = "/landing/services/findAll";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }

    public function payment(Request $request){
 
        $validator = Validator::make($request->all(), [
            'entity_uuid' => 'required',
            'paymode' => 'required',
            'numero_paiement' => 'required',
            'rubrique_facturation_uuid' => 'required',
            'date_visite' => 'required',
            'rdv' => 'required'
        ]);

       // return dd($request->rdv);
        if($validator->fails()){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>"Veuillez renseigner tous les champs",
                'code'=>400,
                'errors' => $validator->errors()
            ];
            return response()->json($dataResponse);
        }
        
        $url_path = "/landing/services/taxe/pay";

        $data = [
            'uuid'=> $request->entity_uuid ?? '',
            'entity_uuid'=> $request->entity_uuid ?? '',
            'paymode'=> $request->paymode ?? '',
            'numero_paiement' => $request->numero_paiement ?? '',
            'rubrique_facturation_uuid' => $request->rubrique_facturation_uuid ?? '',
            'email' => $request->email ?? '',
            'date_visite' => $request->date_visite ?? '',
            'lieu_rdv' => $request->lieu_rdv ?? '',
            'date_rdv' => $request->rdv ?? '',
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       if(isset($response['type'])){
            if($response['type'] =='success'){
                if($request->paymode =="mtn_ci"){
                    $dataResponse =[
                        'type'=>'standby',
                        'urlback'=> $response['urlback'] ?? '',  // route('customer.entities.taxe.info_paiement',['uuid' => $response['data']]),
                        'message'=>$response['message'] ?? '',
                        'reference' => $response['reference'] ?? '',
                        'code'=>200,
                    ];
                    return response()->json($dataResponse);
                }
                else{
                    $dataResponse =[
                        'type'=>'success',
                        'urlback'=> $response['urlback'] ?? '',  // route('customer.entities.taxe.info_paiement',['uuid' => $response['data']]),
                        'message'=>$response['message'] ?? '',
                        'reference' => $response['reference'] ?? '',
                        'code'=>200,
                    ];
                    return response()->json($dataResponse);
                }
            }
            
            elseif($response['type'] =='warning'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>isset($responses['data']['paiement']['uuid']) ? route('landing.entities.taxe.data.info_paiement',['uuid' => $responses['data']['paiement']['uuid']]) : '',
                    'message'=>$response['message'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$response['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$response['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }

        
    public function verificationPaiement($ref){
        $url_path = "/landing/services/taxe/verification/paiement";

        $data = [
            'reference' => $ref
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        Log::info("verification");
        //Log::info(json_encode($responses));

        //return response()->json($responses);
          if(isset($responses['type'])){
            if($responses['type'] =='success'){
                    
                    $dataResponse =[
                        'type'=>'success',
                        'urlback'=> isset($responses['data']['paiement']['uuid']) ? route('landing.entities.taxe.info_paiement',['uuid' => $responses['data']['paiement']['uuid']]) : '',
                        'message'=>$responses['message'] ?? '',
                        'reference' => $ref ?? '',
                        'paymentStatus' =>  isset($responses['data']['paiement']['state']) ? $responses['data']['paiement']['state'] : '',
                        'code'=>200,
                    ];
                    return response()->json($dataResponse);

            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$response['message'] ?? '',
                    'paymentStatus' => '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$response['message'] ?? '',
                'paymentStatus' => '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }

        
    public function info_paiement($uuid)
    {
       
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('info_paiement', [
                    'paiement_uuid' => $uuid,
                ]);
            }
            else{
                return view('info_paiement', [
                    'paiement_uuid' => $uuid,
                ]);
            }
        }
        else{
            return view('info_paiement', [
                'paiement_uuid' => $uuid,
            ]);
        }
    }

        
    public function paiement_data($uuid)
    {
        $url_path = "/landing/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

       $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($responses);
 
    }

    public function generateFile($uuid){
        $url_path = "/landing/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

       $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        // return dd($responses);
       if(!isset($responses['data']['paiement']['uuid']) || $responses['data']['paiement']['state'] !="success"){
            toastr()->error("Veuillez effectuer le paiement afin de pouvoir télécharger le reçu !");
            return redirect()->back();
        }

        $datas = isset($responses['data']['paiement']) ? $responses['data']['paiement'] : '';
        $target = isset($responses['data']['target']) ? $responses['data']['target'] : '';
        $service = isset($responses['data']['service']) ? $responses['data']['service'] : '';
        $pay_element = isset($responses['data']['pay_element']) ? $responses['data']['pay_element'] : '';
        $entete = isset($responses['data']['entete']) ? $responses['data']['entete'] : '';
        $entity = isset($responses['data']['entity']) ? $responses['data']['entity'] : '';
        

        //return dd($responses);

        $filename = Str::slug('RECU PAIEMENT'.$datas['reference'].date('d-m-Y H:i:s'));

       // dd($datas['reference']);
        $quick_ref = explode('|',$datas['reference']);
        $quick_reference = $quick_ref[3];
       // dd($quick_reference);

        $qrcode_text = $pay_element['numero_dimmatriculation'].'|'.$quick_reference;

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($qrcode_text);
        file_put_contents('Qrcode/'.$filename.'.svg', $qrSvg);

        $qrSvg_ = 'Qrcode/'.$filename.'.svg';


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.recu-fiche', ['user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','pay_element' => $pay_element ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference]);
        return $pdf->download($filename.'.pdf');
       
    }

    public function generateRdv($uuid){
        $url_path = "/landing/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

       $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      // return dd($responses);
       if(!isset($responses['data']['paiement']['uuid']) || $responses['data']['paiement']['state'] !="success"){
            toastr()->error("Veuillez effectuer le paiement afin de pouvoir télécharger le reçu !");
            return redirect()->back();
        }

        $datas = isset($responses['data']['paiement']) ? $responses['data']['paiement'] : '';
        $target = isset($responses['data']['target']) ? $responses['data']['target'] : '';
        $service = isset($responses['data']['service']) ? $responses['data']['service'] : '';
        $pay_element = isset($responses['data']['pay_element']) ? $responses['data']['pay_element'] : '';
        $entete = isset($responses['data']['entete']) ? $responses['data']['entete'] : '';
        $entity = isset($responses['data']['entity']) ? $responses['data']['entity'] : '';
        

        //return dd($pay_element);
        //return dd($responses);

        $filename = Str::slug('FICHE RENDEZ-VOUS'.$datas['reference'].date('d-m-Y H:i:s'));


        $quick_ref = explode('|',$datas['reference']);
        $quick_reference = $quick_ref[3];
        
        $qrcode_text = $pay_element['numero_dimmatriculation'].'|'.$quick_reference;


        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($qrcode_text);
        file_put_contents('Qrcode/'.$filename.'.svg', $qrSvg);

        $qrSvg_ = 'Qrcode/'.$filename.'.svg';


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.fiche-de-rdv', ['user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','pay_element' => $pay_element ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference ?? '']);
        return $pdf->download($filename.'.pdf');
       
    }


    public function generateCarte($uuid){
        $url_path = "/landing/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

       $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      // return dd($responses);
       if(!isset($responses['data']['paiement']['uuid']) || $responses['data']['paiement']['state'] !="success"){
            toastr()->error("Veuillez effectuer le paiement afin de pouvoir télécharger le reçu !");
            return redirect()->back();
        }

        $datas = isset($responses['data']['paiement']) ? $responses['data']['paiement'] : '';
        $target = isset($responses['data']['target']) ? $responses['data']['target'] : '';
        $service = isset($responses['data']['service']) ? $responses['data']['service'] : '';
        $pay_element = isset($responses['data']['pay_element']) ? $responses['data']['pay_element'] : '';
        $entete = isset($responses['data']['entete']) ? $responses['data']['entete'] : '';
        $facturation = isset($responses['data']['facturation']) ? $responses['data']['facturation'] : '';

      // return dd($responses['data']);

        $filename = Str::slug('CARTE DE STATIONNEMENT '.$datas['reference'].date('d-m-Y H:i:s'));

        //$qrcode_text = "TAXE DE DISTRICT ".date('Y')." | ref : ".$datas['reference'].' payer le '.date_create($datas['updated_at'])->format('d-m-Y H:i:s');

        
        $quick_ref = explode('|',$datas['reference']);
        $quick_reference = $quick_ref[3];
       // $coupe = substr($quick_reference[3], 2);
        
        $qrcode_text = $pay_element['numero_dimmatriculation'].'|'.$quick_reference;
        $qrcode_text2 = $quick_reference.'|'.$pay_element['numero_dimmatriculation'];

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($qrcode_text);
        file_put_contents('Qrcode/'.$filename.'.svg', $qrSvg);

        $qrSvg_ = 'Qrcode/'.$filename.'.svg';

        /* ################################ */
        $filename2 = Str::slug('CARTE DE STATIONNEMENT2 '.$datas['reference'].date('d-m-Y H:i:s'));

        $qrcode_text2 =  $quick_reference.'|'.$pay_element['numero_dimmatriculation'];
        //$qrcode_text = "TAXE DE DISTRICT ".date('Y')." | ref : ".$datas['reference'].' payer le '.date_create($datas['updated_at'])->format('d-m-Y H:i:s');

        $renderer2 = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer2 = new Writer($renderer2);
        $qrSvg2 = $writer2->writeString($qrcode_text2);
        file_put_contents('Qrcode/'.$filename2.'.svg', $qrSvg2);

        $qrSvg_2 = 'Qrcode/'.$filename2.'.svg';


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.carte', ['pay_element' => $pay_element ?? '','paiement' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "","svgFilePath2" => $qrSvg_2 ?? "","entete" => $entete ?? '',"facturation" => $facturation ?? '','quick_reference' => $quick_reference ?? '']);
        return $pdf->download($filename.'.pdf');
       
    }


    
    public function success_return(Request $request,$uuid){
     //   Log::info(json_encode($request));
        $url_path = "/mobilemoney/verification/hash";
        $data = [
            'hash_ref' =>$uuid,
        ];


        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
    

        if(isset($response['type'])){
            if($response['type'] =='success' && $response['data'] !=""){
                return redirect()->route('landing.entities.taxe.info_paiement',['uuid' => $response['data']]);
            }
            else{
                return view('erreur_paiement');
            }
        }else{
            return view('erreur_paiement');
        }
    }
}
