<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{



    public function securite()
    {
        $url_path = "/autorisations/customers/findOne";

        $data = [
            //'admin_uuid' => UserConnect()['uuid'],
            'uuid' => UserConnect()['uuid']
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       //dd($collaborators);
        $titre="Sécurité du compte ";
        if(isset($collaborators['type'])){
            if($collaborators['type'] =='success'){
                return view('customers.securite.index', [
                    'collaborator'=>$collaborators['data'] ?? '',
                    'titre'=>$titre ?? '',
                    'title'=>'',
                ]);
            }
            else{
                return view('customers.securite.index', [
                    'collaborators'=>$collaborators['data'] ?? '',
                    'titre'=>$titre ?? '',
                    'title'=>'',
                ]);
            }
        }
        else{
            return view('customers.securite.index', [
                'collaborators'=>$collaborators['data'] ?? '',
                'titre'=>$titre,
                'title'=>'',
            ]);
        }
    }

    public function updateAccount(Request $request)
    {
        $url_path = "/autorisations/customers/update";

        $data = [
            'admin_uuid' => userConnect()['uuid'],
            'uuid'=> $request->uuid ?? '',
            'firstname'=> $request->firstname ?? '',
            'lastname'=> $request->lastname ?? '',
            'email'=> UserConnect()['email'] ?? '',
            'phone'=> $request->phone ?? '',
            'civility'=> $request->civility ?? '',
           // 'avatar' => $avatar
        ];

        //return dd("#####");
        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        if(isset($collaborators['type'])){
            $existCollaboratorData = Session::get('user');
            $newCollaboratorData = $collaborators['data']['user'];
            $existCollaboratorData['firstname'] = $newCollaboratorData['firstname'];
            $existCollaboratorData['lastname'] = $newCollaboratorData['lastname'];
            $existCollaboratorData['phone'] = $newCollaboratorData['phone'];
            $existCollaboratorData['avatar'] = $newCollaboratorData['avatar'];

            Session::put('user', $existCollaboratorData);
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
        //return dd('********');
        if($request->password != $request->confirm){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>'Mot de passe différent !',
                'code'=>501,
            ];
            return response()->json($dataResponse);
        }

        $url_path = "/autorisations/customers/update/password";
        $data = [
            //'admin_uuid' => UserConnect()['uuid'],
            'password'=>$request->password ?? '',
            'confirm'=>$request->confirm ?? '',
            'old_password'=>$request->old_password ?? '',
        ];

        $collaborators = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //return dd($collaborators);
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
            $userUUID = UserConnect()['uuid'];

            $file = $request->file('avatar');
            $image = $request->file('avatar');
            $destinationPath = storage_path('app/public/users/avatar/'.$userUUID.'/');
            $imageName = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
           $save = $image->move($destinationPath, $imageName);

            if ($save) {
                $url_path = "/autorisations/customer/update/avatar";

                $data = [
                    //'admin_uuid' => UserConnect()['uuid'],
                    'uuid' => UserConnect()['uuid'],
                    'avatar' => $imageName,
                ];

                $avatar = (new GlobalSendService())->CallApi($url_path,$data,'POST');
               // Log::info($avatar);
               // Session::put('admin', $adminData);
                if(isset($avatar)){
                    if($avatar['type'] =='success'){
                        $adminData = UserConnect();
                        $adminData['avatar'] = $imageName;
                        setUserConnect($adminData);
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

    public function comment_payer(){
        return view('customers.comment-payer');
    }
}
