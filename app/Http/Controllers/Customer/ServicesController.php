<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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

    public function findOneConfig($uuid){

        $url_path = "/autorisations/services/rubrique/findOneConfig";

        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $uuid,
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($response);
        return response()->json($response);
    }
    
}
