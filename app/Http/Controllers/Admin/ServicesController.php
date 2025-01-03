<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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
        if(is_int($request->search)){
            $type = "barre";
            $ref = $request->search;  
        }else{
                $type = "immaticulation";
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
       // return dd($data);

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
        $url_path = "/autorisations/services/admin/show/customer/taxe";

        $data = [
            'uuid' => $uuid,
            'entity_uuid' => $entity_uuid,
        ];
        
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // dd($responses);
        return response()->json($responses);
    }

    
    public function validation($uuid,$entity_uuid,$status){

        $url_path = "/autorisations/services/admin/show/customer/taxe/validate/info";

        $data = [
            'entity_uuid' => $entity_uuid ?? '',
            'uuid' => $uuid ?? '',
            'status' => $status ?? ''
        ];

       // return dd($data);
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($responses);

        return response()->json($responses);
    }

/* STATISTIQUE DATA */

    public function stat_dashboard($uuid)
    {
       // return dd($entity_uuid);
        return view('admins.services.statistique', [
                'Entity_uuid'=>$uuid ?? '',
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
}
