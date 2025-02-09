<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd(AuthConnect()['role']);
        $Entities = Entities();
        if(AuthConnect()['role'] =="PAILLEUR"){
            return redirect()->route('panel.autorisations.statistique.show.data',['uuid' =>$Entities[0]['uuid'], 'type_stat' => 'paiement']);
        }

        
        if(AuthConnect()['role'] =="Superviseurs"){
            return redirect()->route('panel.autorisations.statistique.show.data',['uuid' =>$Entities[0]['uuid'], 'type_stat' => 'validation_jour']);
        }

       // if(AuthConnect()['role'])
        return view('admins.index');
    }

    public function login()
    {
        return view('admins/auth/login');
    }


    public function Connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $data['error'] = true;
            $data['message'] = "Connexion échouée, Veuillez vérifier vos paramètres de connexion";

            $dataResponse =[
                 'type'=> 'error',
                 'urlback'=> '',
                 'message'=> $data['message'] ?? '',
                 'code'=> 500,
                 'step'=> 'Param connexion'
                 ];
                 return response()->json($dataResponse);
        }
        else {

            $url_path = "/authentification/login";
            $data = [
                'email' => $request->email,
                'password' => $request->password,
                'latitude_web' => $request->latitude_web,
                'longitude_web' => $request->longitude_web,
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
           
            //dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                    // Stocker les données dans la session 'admin'
                    unset($clientLogin["data"]["user"]["password"]);

                    Session::put('admin', $clientLogin["data"]["user"]);

                    // Récupérer les données de la session 'admin' et les assigner à des clés spécifiques dans un tableau
                    $adminData = Session::get('admin');
                    $adminData['role'] = $clientLogin["data"]["role"] ?? [];
                    $adminData['offices'] = $clientLogin["data"]["offices"] ?? [];
                    $adminData['permissions'] = $clientLogin["data"]["permissions"] ?? [];
                    $adminData['AccessToken'] = $clientLogin["data"]["token"] ?? [];

                    // Remettre le tableau modifié dans la session 'admin'
                    Session::put('admin', $adminData);

                    $dataResponse =[
                     'type'=>'success',
                     'urlback'=> route('panel.home'),
                     'message'=>$clientLogin['message'] ?? '',
                     'code'=>200,
                 ];
                 return response()->json($dataResponse);
                }
                else {
                     $dataResponse = [
                     'type'=> 'error',
                     'urlback'=> '',
                     'message'=> $clientLogin['message'] ?? 'Connexion echouée ',
                     'code'=>500,
                 ];
                 return response()->json($dataResponse);
                }
            }else{
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $clientLogin['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        //return $data;
    }

    public function otp()
    {
        if(AuthConnect() != null){
            if(AuthConnect()['otp_actif'] !== true){
               return redirect()->route('panel.login');
            }
            return view('admins/auth/otp');
        }
        else{
            return redirect()->route('panel.login');
        }
    }

    public function Otp_Connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            $data['error'] = true;
            $data['message'] = "Connexion échouée, Veuillez vérifier vos paramètres de connexion";

            $dataResponse =[
                'type'=> 'error',
                'urlback'=> '',
                'message'=> $data['message'] ?? '',
                'code'=> 500,
                'step'=> 'Param connexion'
            ];
            return response()->json($dataResponse);
        }
        else {

            $url_path = "/authentification/otp/login";
            $data = [
                'code' => $request->otp,
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
            // return dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                    $user =AuthConnect();
                    $user['otp_actif'] = false;
                   Session::put('admin',  $user);

                    $dataResponse =[
                        'type'=>'success',
                        'urlback'=> route('panel.home'),
                        'message'=>$clientLogin['message'] ?? '',
                        'code'=>200,
                    ];
                    return response()->json($dataResponse);
                }
                else {
                    $dataResponse = [
                        'type'=> 'error',
                        'urlback'=> '',
                        'message'=> $check['message'] ?? 'Connexion echouée ',
                        'code'=>500,
                        'errors' =>$clientLogin['message'] ?? [],
                    ];
                    return response()->json($dataResponse);
                }
            }else{
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $check['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        //return $data;
    }

    public function resend_otp(Request $request)
    {
        $url_path = "/authentification/otp/resend";
        $data = [
            'uuid' => $request->uuid,
        ];

        $OtpResponses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
         //return dd($OtpResponses);

        if(isset($OtpResponses["type"])){
            if ($OtpResponses["type"] == "success") {

                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> "",
                    'message'=>$OtpResponses['message'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else {
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $check['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                    'errors' =>$clientLogin['message'] ?? [],
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse = [
                'type'=> 'error',
                'urlback'=> '',
                'message'=> $check['message'] ?? 'Connexion echouée ',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }


    public function logout_(){



        $username=Session::get('admin')->fistname ?? '';
        Session::forget('admin');
        toastr()->error("Déconnexion effectuée. A bientôt $username !");
        return redirect()->back();
    }

    public function logout(Request $request){
        $url_path = "/authentification/logout";
        $data = [
            
        ];

        $Responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
         //return dd($Responses);

        Session::forget('admin');
        toastr()->success("Déconnexion effectuée. A bientôt !");

        return redirect()->route('panel.login');
    }


    public function controle_logout(Request $request){
        $url_path = "/authentification/logout";
        $data = [
            
        ];

        $Responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
         //return dd($Responses);

        Session::forget('admin');
        toastr()->success("Déconnexion effectuée. A bientôt !");

        return redirect()->route('controle.login');
    }

    public function password_forget() {
        return view('admins/auth/forget');
    }

    public function send_password_forget_email(Request $request) {
        $url_path = "/authentification/generate/forget/link";
        $data = [
            'email' =>$request->email ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        if(isset($check['type'])){
            if($check['type'] =='success'){
                 $dataResponse =[
                     'type'=>'success',
                     'urlback'=>'',
                     'message'=>$check['message'] ?? '',
                     'code'=>200,
                 ];
                 return response()->json($dataResponse);

                /*toastr()->success('Un email de réinitialisation vous a été envoyé a cette adresse email !');
                return redirect()->route('login');*/
            }
            else{
                $dataResponse =[
                      'type'=>'error',
                      'urlback'=>'',
                      'message'=>$check['message'] ?? '',
                      'code'=>500,
                  ];
                  return response()->json($dataResponse);

              /*  toastr()->error("Echec de l'envoi de la demande !");
                return view('auth.passwords.email');*/
            }
        }
        else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=> 'Oups!',
                'code'=>503,
            ];
            return response()->json($dataResponse);
        }

    }

    public function password_reset($token) {
        $url_path = "/authentification/verify/forget/token";
        $data = [
            'token' =>$token ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        // return dd( $check);
        if(isset($check['type'])){
            if($check['type'] == 'success'){
                $data=$check['data'] ?? '';
                $name = $data["civility"] ?? '';
                $name .= $data['firstname'] ?? '';


                toastr()->success($name.' votre demande de réinitialisation approuvé ! Veuillez definir vos nouveaux accès !','Succès');
                return view('admins.auth.password_confirm',['token'=>$data['token'] ?? '']);
            }
            else{
                toastr()->error('Aucune demande de réinitialisation retrouvée !');
                return view('admins.auth.forget');
            }
        }else{
            toastr()->error('Aucune demande de réinitialisation retrouvée !');
            return view('admins.auth.forget');
        }
    }



    public function password_confirm(Request $request) {
        if($request->password != $request->confirm){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>'Mot de passe différent !',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

        $url_path = "/authentification/reset/password";
        $data = [
            'password' =>$request->password ?? '',
            'confirm' =>$request->confirm ?? '',
            'token' =>$request->token ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($check);
        if(isset($check['type'])){
            if($check['type'] =='success'){

                if($check['data']['who_is'] =="agent"){
                    $route = route('controle.home');
                }else{
                    $route = route('panel.home');
                }

                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>$route ?? route('panel.home'),
                    'message'=>$check['message'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$check['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        else{

        }

    }
}
