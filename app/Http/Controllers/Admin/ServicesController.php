<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class ServicesController extends Controller
{

    public function index($uuid)
    {
       // return dd($entity_uuid);
        return view('admins.services.index', [
                'Entity_uuid'=>$uuid ?? '',
        ]);
      
    }

    public function rendez_vous(){

        $Entity = Entities()[0] ?? '';
        //dd($Entity);
        return view('admins.services.rendez-vous',['Entity_uuid' => $Entity['uuid'] ?? '']);
    }

    
    public function liste_rdv(){

        $Entity = Entities()[0] ?? '';
        //dd($Entity);
        return view('admins.services.liste_rendez-vous',['Entity_uuid' => $Entity['uuid'] ?? '']);
    }
    
    public function rdv_activite($uuid){

        $Entity = Entities()[0] ?? '';
        //dd($Entity);
        return view('admins.services.rdv_activite',['Entity_uuid' => $uuid ?? '']);
    }

    public function rdv_today_activite($uuid){
        $url_path = "/autorisations/entite/taxes/rdv/today/activite";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($responses);

        return response()->json($responses); 
    }

    public function rdv_today_historique_activite($uuid){
        return view('admins.services.rdv_resultat_traitement',['Entity_uuid' => $uuid ?? '']);
   }

   public function rdv_historique_activites($uuid){
        return view('admins.services.rdv_historique_traitement',['Entity_uuid' => $uuid ?? '']);
   }

    public function rdv_historique_activites_data($uuid){
        $url_path = "/autorisations/entite/taxes/rdv/historique/activite/data";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       //return dd($responses);

        return response()->json($responses); 
    }
    

    public function stat_rdv($uuid){
        $url_path = "/autorisations/entite/taxes/rdv/findAllStatistique";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //  return dd($responses);

        return response()->json($responses); 
    }


    
    public function rdv_findAll($uuid){
        $url_path = "/autorisations/entite/taxes/rdv/findAll";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //  return dd($responses);

        return response()->json($responses);  
    }

    public function rdv_search(Request $request){
        $url_path = "/autorisations/entite/taxes/rdv/search";

       // $type = checkRef($request->search);
       $search = explode('DIS|TSA-',$request->search);
       if(isset($search[1])){
            $type = "reference";
            $ref = $request->search;
            
       }else{
      //  dd(is_int($request->search));
        if ((int)$request->search == $request->search) {
            $type = "barre";
            $ref = (int)$request->search;
        }
        else {
                $type = "immatriculation";
                $ref = $request->search;
        }


       }
       
     //  dd($search);

        $data = [
            'entity_uuid' => $request->entity_uuid ?? '',
            'search' => $ref ?? '',
            'type' => $type ?? ''
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($data);

        if($responses['type'] == 'error'){
            return response()->json($responses);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $dataResponse['message'] ?? "Un élément retrouvé",
                'code' => 200,
                'urlback'=> route('panel.autorisations.services.taxes.show',['uuid'=>$responses['data']['pay_uuid'],'entity_uuid'=>$responses['data']['entity_uuid']]),
                'data' => $dataResponse['data'] ?? ''
            ]);
        } 

       // return response()->json($responses);  
    }

    public function findAll($uuid){
        $url_path = "/autorisations/entite/taxes/findAll";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //  return dd($responses);

        return response()->json($responses);  
    }


    public function show($uuid,$entity_uuid)
    {
      //  return dd($entity_uuid);
        return view('admins.services.show', [
                'entity_uuid'=>$entity_uuid ?? '',
                'element_uuid' =>$uuid ?? '',
            ]);
      
    }

    public function search(Request $request){

        $url_path = "/autorisations/entite/taxes/search/findAll";

        $data = [
            'entity_uuid' => $request->entity_uuid ?? '',
            'rubrique_facturation_uuid' => $request->rubrique_facturation_uuid ?? '',
            'dateBegin' => $request->dateBegin ?? '',
            'dateEnd' => $request->dateEnd ?? '',
            'status' => $request->status ?? ''
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($responses);

        return response()->json($responses);  
    }




    public function statistique($uuid)
    {
        $url_path = "/autorisations/entite/taxes/statistique";

        $data = [
            'entity_uuid' => $uuid
        ];

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

      // dd($responses);
        return response()->json($responses);
    }


    public function find_service($uuid,$entity_uuid){
        $url_path = "/autorisations/admin/services/show/taxe";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
        ];
        
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //dd($responses);
        return response()->json($responses);
    }

    
    public function validation($uuid,$entity_uuid,$status,$motif=''){

        $url_path = "/autorisations/admin/services/show/validate/taxe/info";

        $data = [
            'entity_uuid' => $entity_uuid ?? '',
            'uuid' => $uuid ?? '',
            'status' => $status ?? '',
            'motif' => $motif,
        ];

       // return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($responses);
        Log::info(json_encode($responses));
        return response()->json($responses);
    }

/* STATISTIQUE DATA */

    public function stat_dashboard($uuid,$type_stat)
    {
       // return dd($entity_uuid);
        return view('admins.services.statistique', [
                'Entity_uuid'=>$uuid ?? '',
                'type_stat' => $type_stat ?? ''
        ]);
      
    }

    
    public function statistique_dashboard($uuid,$type_stat)
    {
       // return dd($entity_uuid);
        return view('admins.services.statistique-dashboard', [
                'Entity_uuid'=>$uuid ?? '',
                'type_stat' => $type_stat ?? ''
        ]);
      
    }

    public function stat_data($entity)
    {

        $url_path = "/autorisations/statistiques/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $entity ?? ''

        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // dd($dataResponse);
        return response()->json($dataResponse);
    }    


    public function data_rdv($entity,$rdv)
    {

        $url_path = "/autorisations/statistiques/rendez-vous/data";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $entity ?? '',
            'rdv' => $rdv ?? '',
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // dd($dataResponse);
        return response()->json($dataResponse);
    }

    
    public function stat_find_data($status,$paymode,$entity){
        $url_path = "/autorisations/statistiques/find_data";
        $list = array("MTN"=>'mtn_ci',"ORANGE" => 'orange_ci',"WAVE" => 'wave_ci',"MOOV" => 'moov_ci',"TRESOR" => 'tresor_ci',"ALL" => 'all');
        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'status' => $status ?? 'today',
            'paymode' => $list[$paymode] ?? "all",
            'entity_uuid' => $entity ?? ''
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function data_validationJ($entity,$day='all'){
        $url_path = "/autorisations/statistiques/validationJ/find_data";
        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'status' => $status ?? 'today',
            'day' => $day ?? "all",
            'entity_uuid' => $entity ?? ''
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        //dd($dataResponse);
        return response()->json($dataResponse);
    } 
    
    public function data_validateur($entity){
        $url_path = "/autorisations/statistiques/validateur/find_data";
        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $entity ?? ''
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function caisse($entity_uuid){
        return view('admins.services.ajout_usager', [
            'Entity_uuid'=>$entity_uuid ?? '',
        ]);
    }

    public function caisse_store(Request $request){
 
        $validator = Validator::make($request->all(), [
            'entity_uuid' => 'required',
            //'paymode' => 'required',
            //'numero_paiement' => 'required',
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
        
        $url_path = "/autorisations/entite/taxes/caisse";

        $Entity = Entities()[0] ?? '';

       
        $data = [
            'entity_uuid'=> $Entity['uuid'] ?? '',
            'paymode'=> 'cash',
           // 'numero_paiement' => $request->numero_paiement ?? '',
            'rubrique_facturation_uuid' => $request->rubrique_facturation_uuid ?? '',
            'email' => $request->email ?? '',
            'date_visite' => $request->date_visite ?? '',
            'lieu_rdv' => $request->lieu_rdv ?? '',
            'date_rdv' => $request->rdv ?? '',
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // dd($response);
       if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('panel.autorisations.services.taxes.show',[
                        'uuid' => $response['element_uuid'] ?? '',
                        'entity_uuid' => $Entity['uuid'] ?? ''
                    ]),
                    'message'=>$response['message'] ?? '',
                    'reference' => $response['reference'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$response['message'] ?? '',
                    'errors' => $response['errors'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$response['message'] ?? '',
                'errors' => $response['errors'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }

    public function service_update(Request $request)
    {
        $url_path = "/autorisations/entite/taxes/update";

        $data = [
            'uuid'=> $request->entity_uuid ?? '',
            'element_uuid' => $request->uuid,
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        
       // return dd($response);
        //return response()->json($response);


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


    public function service_store(Request $request)
    {
        $url_path = "/autorisations/entite/taxes/store";

        $data = [
            'uuid'=> $request->entity_uuid ?? '',
            'user_uuid' => $request->user_uuid ?? '',
            'element'=> $request->all(),
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        
       // return dd($response);
        //return response()->json($response);


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


    public function cheque(){

        $Entity = Entities()[0] ?? '';
        //dd($Entity);
        return view('admins.services.reception-cheque',['Entity_uuid' => $Entity['uuid'] ?? '']);
    }

    public function cheque_liste(){

        $Entity = Entities()[0] ?? '';
        //dd($Entity);
        return view('admins.services.reception-cheque',['Entity_uuid' => $Entity['uuid'] ?? '']);
    }

    public function cheque_store(Request $request){
        $url_path = "/autorisations/entite/taxes/cheque/store";
        $data = [
            'entity_uuid' => $request->entity_uuid ?? '',
            'check_number' => $request->check_number ?? '',
            'banque_emettrice' => $request->banque_emettrice ?? '',
            'date_emission' => $request->date_emission ?? '',
            'montant_cheque' => $request->montant_cheque ?? '',
            'titulaire_compte' => $request->titulaire_compte ?? '',
            'type' => $type ?? '',
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($data);

        if($responses['type'] == 'error'){
            return response()->json($responses);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $responses['message'] ?? "Un élément retrouvé",
                'code' => 200,
                'urlback'=> route('panel.autorisations.services.taxes.show',['uuid'=>$responses['data']['pay_uuid'],'entity_uuid'=>$responses['data']['entity_uuid']]),
                'data' => $responses['data'] ?? ''
            ]);
        }
    }

    public function cheque_update(Request $request){
        $url_path = "/autorisations/entite/taxes/cheque/update";
        $data = [
            'uuid' => $request->cheque_uuid ?? '',
            'entity_uuid' => $request->entity_uuid ?? '',
            'status' => 'pending',
            'numero_cheque' => $request->numero_cheque ?? '',
            'banque_emettrice' => $request->banque_emettrice ?? '',
            'date_emission' => $request->date_emission ?? '',
            'montant_cheque' => $request->montant_cheque ?? '',
            'titulaire_compte' => $request->titulaire_compte ?? '',
            'type' => $type ?? '',
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($data);

        if($responses['type'] == 'error'){
            return response()->json($responses);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $responses['message'] ?? "Un élément retrouvé",
                'code' => 200,
                'urlback'=> '',//
                'data' => $responses['data'] ?? ''
            ]);
        }
    }

    public function cheque_search(Request $request){

        $search = explode('DIS|TSA-', $request->search);

        if (isset($search[1])) {
            $type = "reference";
            $ref = $request->search;
        } else {
            // Vérifie si le format correspond à un numéro de compte contribuable (NCC)
            if (preg_match('/^\d{2}\.\d{3}\.\d{3}\.\d{1}$/', $request->search)) {
                $type = "contribuable";
                $ref = $request->search;
            }
            // Vérifie si c'est un NIF
            elseif (preg_match('/^CI-\d{3}-\d{3}-\d{3}$/', $request->search)) {
                $type = "contribuable";
                $ref = $request->search;
            }
            // Si c'est un entier pur (code-barres)
            elseif (is_numeric($request->search)) {
                $type = "barre";
                $ref = (int) $request->search;
            } 
            // Sinon, on considère comme un numéro d'immatriculation
            else {
                $type = "nom_entreprise";
                $ref = $request->search;
            }
        }




        $url_path = "/autorisations/entite/taxes/cheque/search";
        $data = [
            'entity_uuid' => $request->entity_uuid ?? '',
            'search' => $ref ?? '',
            'type' => $type ?? ''
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($responses);

        if($responses['type'] == 'error'){
            return response()->json($responses);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $responses['message'] ?? "Un élément retrouvé",
                'code' => 200,
                'urlback'=> route('panel.autorisations.services.taxes.cheque.show',['cheque_uuid'=>$responses['data']['cheque_uuid'],'entity_uuid'=>$responses['data']['entity_uuid']]),
                'data' => $responses['data'] ?? ''
            ]);
        }
    }

    public function cheque_show($cheque_uuid,$entity_uuid){
        return view('admins.services.cheque-show', [
            'entity_uuid'=>$entity_uuid,
            'cheque_uuid'=>$cheque_uuid,
        ]);
    }



    public function chequeData($cheque_uuid,$entity_uuid){
        $url_path = "/autorisations/entite/taxes/cheque/data";

        $data = [
            'cheque_uuid' => $cheque_uuid,
            'entity_uuid' => $entity_uuid,
        ];
        
        //return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

    public function cheque_cotation($uuid){
        $url_path = "/autorisations/entite/taxes/cheque/submit/cotation";

        $data = [
            'uuid' => $uuid,
        ];
       // return dd($uuid);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($responses);
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
        $pdf->loadView('pdf.fiche-cotation', ['user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference]);
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

       // return dd($responses);
        //return response()->json($responses);



        
        $datas = isset($responses['cheque']) ? $responses['cheque'] : '';
        $payElement = isset($responses['data']) ? $responses['data'] : '';
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
        $pdf->loadView('pdf.facture-cotation', ['payElement' => $payElement,'user' => $datas ?? '','target' => $target ?? '','service' => $service ?? '','entity' => $entity ?? '','entete' => $entete ?? '','open'=>true,"pdf" => true,"svgFilePath" => $qrSvg_ ?? "",'quick_reference' => $quick_reference]);
        return $pdf->download($filename.'.pdf');
       
    }

    public function valider_ligne_cotation($uuid,$entity_uuid){
        $url_path = "/autorisations/entite/taxes/cheque/validation/ligne/cotation";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
            'status' => 'validate'
        ];
       // return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

    public function valider_cotation($uuid,$entity_uuid){
        $url_path = "/autorisations/entite/taxes/cheque/validation/cotation";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
            'status' => 'cotation'
        ];
       // return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

    public function valider_cheque(Request $request){
        $url_path = "/autorisations/entite/taxes/cheque/valider";

        $data = [
            'uuid' => $request->uuid,
            'entity_uuid' => $request->entity_uuid,
            'motif' => $request->motif ?? '',
            'status' => 'validate',
            'date_encaissement' => $request->date_encaissement ?? '',
        ];
        //return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($responses);
        return response()->json($responses);
    }

    
    public function annuler_cheque($uuid,$entity_uuid){
        $url_path = "/autorisations/entite/taxes/cheque/annuler";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
            'status' => 'fail'
        ];
       // return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        //return dd($responses);
        return response()->json($responses);
    }

}
