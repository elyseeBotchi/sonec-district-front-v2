<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class AgentsController extends Controller
{

    public function index(Request $request)
    {

        $titre="Liste des agents ";

        return view('admins.configurations.agents.index', [
            'titre'=>$titre,
        ]);

    }

    public function findAll(Request $request)
    {
        $url_path = "/autorisations/agents/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid']
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // return dd($collaborators);
        return response()->json($collaborators);
    }

    public function findAllOffice(Request $request, $uuid)
    {
        $url_path = "/autorisations/agents/$uuid/findAllOffice";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid ?? ''
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');
      //  return dd($collaborators);
        return response()->json($collaborators);
    }

    public function show(Request $request, $uuid)
    {
        $url_path = "/autorisations/agents/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=>$uuid,
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // return dd($collaborators);
        return view('admins.configurations.agents.show', [
            'user' => json_decode(json_encode($collaborators['data']))
        ]);
    }

    public function change(Request $request, $uuid){

        $url_path = "/autorisations/agents/changeStatus";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($collaborators);
    }


    public function officeChangeStatus(Request $request, $uuid){
        $url_path = "/autorisations/agents/officeChangeStatus";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid,
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // dd($collaborators);

        return response()->json($collaborators);
    }


    public function store(Request $request)
    {
        $url_path = "/autorisations/agents/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'firstname'=>$request->firstname ?? '',
            'lastname'=>$request->lastname ?? '',
            'email'=>$request->email ?? '',
            'matricule'=>$request->matricule ?? '',
            'who_is'=> 'agent',
            'phone'=>$request->phone ?? '',
            'civility'=>$request->civility ?? '',
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        if($dataResponse['type'] == 'error'){
            return response()->json($dataResponse);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => "Un élément enregistré",
                'code' => 200,
                'urlback'=>''
            ]);
        }
    }

    public function update(Request $request, $uuid)
    {
        $url_path = "/autorisations/agents/update";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=> $uuid ?? '',
            'firstname'=>$request->firstname ?? '',
            'lastname'=>$request->lastname ?? '',
            'email'=>$request->email ?? '',
            'phone'=>$request->phone ?? '',
            'matricule'=>$request->matricule ?? '',
            'avatar' => $request->file('avatar')
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function storeRole(Request $request, $uuid){
        $url_path = "/autorisations/agents/office-store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'role_uuid'=> $request->role_uuid ?? '',
            'uuid'=> $uuid ?? '',
            'heading_uuid'=>$request->heading_uuid ?? '',
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function lock(Request $request)
    {
        $url_path = "/autorisations/agents/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=>$request->id ?? '',
            'status'=>$request->param ?? ''
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($collaborators);
        if(isset($collaborators['type'])){
            if($collaborators['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>route('panel.autorisations.collaborateurs.findOne',['uuid'=>$collaborators['data']['uuid']]),
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>200,
                ];
            return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$collaborators['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }

    public function securite()
    {
        $url_path = "/autorisations/agents/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => AuthConnect()['uuid']
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       //dd(AuthConnect());
        $titre="Sécurité du compte ";
        if(isset($collaborators['type'])){
            if($collaborators['type'] =='success'){
                return view('admins.securite.index', [
                    'collaborator'=>$collaborators['data'] ?? '',
                    'titre'=>$titre ?? '',
                    'title'=>'',
                ]);
            }
            else{
                return view('admins.securite.index', [
                    'collaborators'=>$collaborators['data'] ?? '',
                    'titre'=>$titre ?? '',
                    'title'=>'',
                ]);
            }
        }
        else{
            return view('admins.securite.index', [
                'collaborators'=>$collaborators['data'] ?? '',
                'titre'=>$titre,
                'title'=>'',
            ]);
        }
    }
    public function updateAccount(Request $request)
    {
        $url_path = "/autorisations/agents/".$request->uuid."/update";

        /* $file = $request->file('avatar');

       if ($request->hasFile('avatar')) {
            $filenameWithoutExtension = Str::uuid()->toString();
            $filename = $filenameWithoutExtension.'.'.$file->getClientOriginalExtension(); // Créer un nom de fichier unique
            $file->storeAs('admins', $filename, 'public'); // Enregistrez le fichier dans storage/app/public/files

            $avatar = $filename;

        }else{
            $avatar = NULL;
        }*/

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=>$request->uuid ?? '',
            'firstname'=>$request->firstname ?? '',
            'lastname'=>$request->lastname ?? '',
            'email'=>AuthConnect()['email'] ?? '',
            'phone'=>$request->phone ?? '',
            'civility'=>$request->civility ?? '',
           // 'avatar' => $avatar
        ];

        //return dd("#####");
        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        if(isset($collaborators['type'])){
            $existCollaboratorData = Session::get('admin');
            $newCollaboratorData = $collaborators['data']['user'];
            $existCollaboratorData['firstname'] = $newCollaboratorData['firstname'];
            $existCollaboratorData['lastname'] = $newCollaboratorData['lastname'];
            $existCollaboratorData['phone'] = $newCollaboratorData['phone'];
            $existCollaboratorData['avatar'] = $newCollaboratorData['avatar'];

            Session::put('admin', $existCollaboratorData);
            if($collaborators['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'back',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>200,
                ];
            return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$collaborators['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }

    public function updatePassword(Request $request)
    {

        if($request->password != $request->confirm){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>'Mot de passe différent !',
                'code'=>501,
            ];
            return response()->json($dataResponse);
        }

        $url_path = "/autorisation/agents/update/password";
        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'password'=>$request->password ?? '',
            'confirm'=>$request->confirm ?? '',
            'old_password'=>$request->old_password ?? '',
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      return dd($collaborators);
        if(isset($collaborators['type'])){
            if($collaborators['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'back',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>200,
                ];
            return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$collaborators['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$collaborators['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }


    public function uploadAvatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required|image|max:2048', // Taille maximale de 2MB
        ]);

        if ($request->hasFile('avatar')) {
            $userUUID = AuthConnect()['uuid'];

            $file = $request->file('avatar');
            $image = $request->file('avatar');
            $destinationPath = storage_path('app/public/users/avatar/'.$userUUID.'/');
            $imageName = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
           $save = $image->move($destinationPath, $imageName);

            if ($save) {
                $url_path = "/autorisations/agents/update/avatar";

                $data = [
                    'admin_uuid' => AuthConnect()['uuid'],
                    'uuid' => AuthConnect()['uuid'],
                    'avatar' => $imageName,
                ];

                $avatar = (new GlobalSendService())->CallApi($url_path,$data,'POST');
                //Log::info($avatar);
               // Session::put('admin', $adminData);
                if(isset($avatar)){
                    if($avatar['type'] =='success'){
                        $adminData = AuthConnect();
                        $adminData['avatar'] = $imageName;
                        setAuthConnect($adminData);
                    }
                }

                return response()->json($avatar);
            }
            else {
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=> "Erreur lors de l'enregistrement du fichier. ",
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=> "Aucun fichier fourni ou fichier invalide.",
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }


    public function activity_dashboard(){
        return view('admins.activities.index');
    }
    

    public function activity_load(){
        $url_path = '/autorisations/entite/activities/findAll';
        
        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
        ];
       
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'GET');
      //  return dd( $responses);    

        return response()->json($responses);
    }
    
    public function activity_find(Request $request){
        $url_path = '/autorisations/entite/activities/filtre';
        
        $data = [
            'dateBegin' => $request->dateBegin ?? '',
            'dateEnd' => $request->dateEnd ?? '',
            'status' => $request->status ?? '',
            'agent' => $request->agent ?? '',
            'entity_uuid' => $request->entity_uuid ?? ''
        ];

       // return dd('*****');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($responses);
        return response()->json($responses);
    }

    public function activity_statistique(){
        $url_path = '/autorisations/entite/activities/statistique';
        
        $data = [
            'admin_uuid' => '',
        ];

       // return dd('******');
        $responses = (new GlobalSendService())->CallApi($url_path,$data,'GET');
        return response()->json($responses);
    }
}
