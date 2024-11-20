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

      // dd($responses);
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
           // 'uuid' => $service
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('quickPayForm', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? ''
                ]);
            }
            else{
                return view('quickPayForm', [
                    'operateurs'=>$responses['data'] ?? '',
                    'service_uuid' => $service ?? '',
                    'service_name' => $name ?? ''

                ]);
            }
        }
        else{
            return view('quickPayForm', [
                'operateurs'=>$responses['data'] ?? '',
                'service_uuid' => $service ?? '',
                'service_name' => $name ?? ''

            ]);
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
        ]);

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
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return response()->json($response);

        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('landing.entities.taxe.info_paiement',['uuid' => $response['data']]),
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
        

        //return dd($responses['data']);

        $filename = Str::slug('RECU DE TAXE '.$datas['reference'].date('d-m-Y H:i:s'));

        $qrcode_text = "T-CONNECT | RECU DE TAXE ".date('Y')." | ref : ".$datas['reference'].' payer le '.date_create($datas['updated_at'])->format('d-m-Y H:i:s');

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
        $pdf->loadView('pdf.recu-fiche', ['user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? ""]);
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

        $filename = Str::slug('RECU DE TAXE '.$datas['reference'].date('d-m-Y H:i:s'));

        $qrcode_text = "T-CONNECT | RECU DE TAXE ".date('Y')." | ref : ".$datas['reference'].' payer le '.date_create($datas['updated_at'])->format('d-m-Y H:i:s');

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
        $pdf->loadView('pdf.carte', ['pay_element' => $pay_element ?? '','paiement' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "","entete" => $entete ?? '',"facturation" => $facturation ?? '']);
        return $pdf->download($filename.'.pdf');
       
    }


}
