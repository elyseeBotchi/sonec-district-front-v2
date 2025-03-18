<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class ServicesController extends Controller
{

    public function index($slug,$target)
    {
        $url_path = "/autorisations/services/taxe/operateurs";

        $data = [
            'entity_uuid' => $target
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      // dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('customers.services.index', [
                    'operateurs'=>$responses['data'] ?? '',
                    'entity_uuid' =>$target ?? '',
                    'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                    'lieuRdv' => $responses['lieuRdv'] ?? '',
                    'limit' => $datas['limit'] ?? 5

                ]);
            }
            else{
                return view('customers.services.index', [
                    'operateurs'=>$responses['data'] ?? '',
                    'entity_uuid' =>$target ?? '',
                    'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                    'lieuRdv' => $responses['lieuRdv'] ?? '',
                    'limit' => $datas['limit'] ?? 5

                ]);
            }
        }
        else{
            return view('customers.services.index', [
                'operateurs'=>$responses['data'] ?? '',
                'entity_uuid' =>$target ?? '',
                'dateValideRdv' => $responses['dateValideRdv'] ?? '',
                'lieuRdv' => $responses['lieuRdv'] ?? '',
                'limit' => $datas['limit'] ?? 5
            ]);
        }
    }


    public function findAll($uuid)
    {
        $url_path = "/autorisations/services/taxe/findAll";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }

    public function entete($uuid)
    {
        $url_path = "/services/findEntete";

        $data = [
            'uuid' => $uuid
        ];

        //return response()->json($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }    

    public function findEntete($uuid)
    {
        $url_path = "/autorisations/services/taxe/findEntete";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }    
    
    public function findOne($uuid)
    {
        $url_path = "/autorisations/services/taxe/findOne";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }

    public function findAllEntite()
    {
        $url_path = "/autorisations/services/findAll";

        $data = [
            'uuid' => AuthConnect()['uuid'] ?? ''
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       //dd($responses);
        return response()->json($responses);
    }

    public function show($uuid,$entity_uuid)
    {
       // return dd($entity_uuid);
        return view('customers.services.show', [
                'entity_uuid'=>$entity_uuid ?? '',
                'element_uuid' =>$uuid ?? '',

            ]);
      
    }

    public function find_service($uuid,$entity_uuid){
        $url_path = "/autorisations/services/taxe/show";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
        ];
        
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        return response()->json($responses);
    }

    public function store(Request $request)
    {
        $url_path = "/autorisations/services/taxe/store";

        $data = [
            'uuid'=> $request->entity_uuid ?? '',
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        return response()->json($response);


        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'back',
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

    
    public function update(Request $request)
    {
        $url_path = "/autorisations/services/taxe/update";

        $data = [
            'uuid'=> $request->entity_uuid ?? '',
            'element_uuid' => $request->uuid,
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        
       // return dd($response);
        return response()->json($response);


        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'back',
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

    public function delete($uuid,$entity_uuid)
    {
        $url_path = "/autorisations/services/taxe/delete";

        $data = [
            'uuid'=> $uuid,
            'entity_uuid'=> $entity_uuid ?? '',
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        return response()->json($response);


        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'',
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


    public function findOneConfig($uuid)
    {
        $url_path = "/autorisations/services/rubrique/findOneConfig";
        $cache_key = "rubrique_config_{$uuid}"; // Définir une clé de cache unique basée sur l'UUID

        // Vérifier ou récupérer depuis le cache
        $response = Cache::remember($cache_key, 1440, function () use ($url_path, $uuid) {
            $data = [
                'entity_uuid' => $uuid,
            ];

            // Appel à l'API
        //  Log::info("Appel API pour récupérer la rubrique config pour l'entité : {$uuid}");

            return (new GlobalSendService())->CallApi($url_path, $data, 'POST');
        });

        // Vérifier si la réponse provient du cache ou de l'API
        if (Cache::has($cache_key)) {
        // Log::info("Données récupérées depuis le cache pour findOneConfig : {$uuid}");
        } else {
        // Log::info("Données récupérées depuis l'API pour findOneConfig : {$uuid}");
        }

        // Si la réponse contient des données valides, la retourner
        if (isset($response['type']) && $response['type'] === 'success') {
            return response()->json($response);
        }

        // Forcer un nouvel appel à l'API si la réponse est invalide
        $newResponse = (new GlobalSendService())->CallApi($url_path, ['entity_uuid' => $uuid], 'POST');

        // Si le nouvel appel réussit, mettre à jour le cache et retourner les données
        if (isset($newResponse['type']) && $newResponse['type'] === 'success') {
            Cache::put($cache_key, $newResponse, 60); // Met à jour le cache avec les nouvelles données
        // Log::info("Données mises à jour dans le cache pour l'entité : {$uuid}");
            return response()->json($newResponse);
        }

        // Si tout échoue, retourner une valeur par défaut
        Log::error("Erreur lors de la récupération des données pour l'entité : {$uuid}");
        return response()->json(['message' => 'Erreur lors de la récupération des données'], 500);
    }

    public function cheque($target){

        return view('customers.services.cheque', [
            'entity_uuid'=>$target ?? '',
        ]); 
    }

    public function findCheque($uuid){
        $url_path = "/autorisations/services/taxe/cheque/find";

        $data = [
            'entity_uuid' => $uuid,
        ];
        
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        return response()->json($responses);
    }

    public function cheque_store(Request $request){
        if($request->contribuable ==""){
            return response()->json([
                'type' => 'error',
                'message' => 'Le numéro contribuable est requis',
                'code' => 400,
            ], 400);
        }

        $url_path = "/autorisations/services/taxe/cheque/store";

        $data = [
            'entity_uuid' => $request->entity_uuid,
            'libelle' => $request->libelle,
            'nom_du_proprietaire' => $request->nom_du_proprietaire,
            'contribuable' => $request->contribuable,
            'nombre_vehicule' => $request->nombre_vehicule,
            'telephone' => $request->telephone,
        ];
        
        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // dd($response);

        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('customer.entities.taxe.cheque.detail',[
                        'uuid' => $response['data']['uuid'],
                        'entity_uuid' =>$request->entity_uuid
                    ]),
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
   
    
    public function cheque_update(Request $request){
        if($request->contribuable ==""){
            return response()->json([
                'type' => 'error',
                'message' => 'Le numéro contribuable est requis',
                'code' => 400,
            ], 400);
        }

        $url_path = "/autorisations/services/taxe/cheque/update";

        $data = [
            'uuid' => $request->uuid,
            'entity_uuid' => $request->entity_uuid,
            'libelle' => $request->libelle,
            'nom_du_proprietaire' => $request->nom_du_proprietaire,
            'contribuable' => $request->contribuable,
            'nombre_vehicule' => $request->nombre_vehicule,
            'telephone' => $request->telephone,
        ];
        
        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // dd($response);

        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('customer.entities.taxe.cheque.detail',[
                        'uuid' => $response['data']['uuid'],
                        'entity_uuid' =>$request->entity_uuid
                    ]),
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
    
    
    public function chequeDetail($uuid,$entity_uuid){

        return view('customers.services.cheque_detail', [
            'entity_uuid'=>$entity_uuid ?? '',
            'cheque_uuid'=>$uuid ?? '',
        ]); 
    }


    public function chequeData($cheque_uuid,$entity_uuid){
        $url_path = "/autorisations/services/taxe/cheque/data";

        $data = [
            'cheque_uuid' => $cheque_uuid,
            'entity_uuid' => $entity_uuid,
        ];
        
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

    public function cheque_cotation($uuid){
        $url_path = "/autorisations/services/taxe/cheque/submit/cotation";

        $data = [
            'uuid' => $uuid,
        ];
       // return dd($uuid);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($responses);
        return response()->json($responses);
    }

    public function cheque_remove_cotation($uuid,$element_uuid){
        $url_path = "/autorisations/services/taxe/cheque/remove/cotation";

        $data = [
            'uuid' => $uuid,
            'element_uuid' => $element_uuid

        ];
       // return dd($uuid);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

    public function fiche_cotation($uuid,$entity_uuid){
        $url_path = "/autorisations/services/taxe/cheque/data";

        $data = [
            'entity_uuid' => $entity_uuid,
            'cheque_uuid' => $uuid,
        ];
       // return dd($uuid);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($responses);
        //return response()->json($responses);



        $payElement = isset($responses['data']) ? $responses['data'] : '';

        $datas = isset($responses['cheque']) ? $responses['cheque'] : '';
        $target = isset($responses['target']) ? $responses['target'] : '';
        $service = isset($responses['services']) ? $responses['services'] : '';
        $entete = isset($responses['entete']) ? $responses['entete'] : '';
        $entity = isset($responses['entity']) ? $responses['entity'] : '';
        

        //return dd($responses['data']);

        $filename = Str::slug('FICHE DE COTATION'.$datas['reference'].date('d-m-Y H:i:s'));

       // dd($datas['reference']);
        $quick_ref = explode('|',$datas['reference']);
        $quick_reference = $quick_ref[3];
       // dd($quick_reference);

        $qrcode_text = $datas['contribuable'].'|'.$quick_reference;

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($qrcode_text);
        file_put_contents('Qrcode/cotations/'.$filename.'.svg', $qrSvg);

        $qrSvg_ = 'Qrcode/cotations/'.$filename.'.svg';


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.fiche-cotation', ['payElement' => $payElement ?? '','user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference]);
        return $pdf->download($filename.'.pdf');
       
    }

    public function facture_cotation($uuid,$entity_uuid){
        $url_path = "/autorisations/services/taxe/cheque/data";

        $data = [
            'entity_uuid' => $entity_uuid,
            'cheque_uuid' => $uuid,
        ];
       // return dd($uuid);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        //return response()->json($responses);



        
        $datas = isset($responses['cheque']) ? $responses['cheque'] : '';
        $payElement = isset($responses['data']) ? $responses['data'] : '';
        $target = isset($responses['target']) ? $responses['target'] : '';
        $service = isset($responses['services']) ? $responses['services'] : '';
        $entete = isset($responses['entete']) ? $responses['entete'] : '';
        $entity = isset($responses['entity']) ? $responses['entity'] : '';
        

       

        $filename = Str::slug('FICHE DE COTATION'.$datas['reference'].date('d-m-Y H:i:s'));

       // dd($datas['reference']);
        $quick_ref = explode('|',$datas['reference']);
        $quick_reference = $quick_ref[3];
       // dd($quick_reference);

        $qrcode_text = $datas['contribuable'].'|'.$quick_reference;

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($qrcode_text);
        file_put_contents('Qrcode/cotations/'.$filename.'.svg', $qrSvg);

        $qrSvg_ = 'Qrcode/cotations/'.$filename.'.svg';


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.facture-cotation', ['payElement' => $payElement,'user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference,'vehicule_enregistre' => count($payElement)]);
        return $pdf->download($filename.'.pdf');
       
    }


    
}
