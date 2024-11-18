<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function index(){

        return view('admins.configurations.roles.index');
    }

    public function store(Request $request){
        $url_path = "/autorisations/roles/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function findAll(Request $request){
        $url_path = "/autorisations/roles/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function edit($uuid){
        $url_path = "/autorisations/roles/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');
       // return dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function update(Request $request, $uuid){
        $url_path = "/autorisations/roles/update";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name,
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function delete($uuid){

        $url_path = "/autorisations/roles/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function permissionIndex($uuid)
    {

        $url_path = "/autorisations/roles/findAllPermissions";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        $data = json_decode(json_encode($dataResponse['data']));
        //return dd($data->modules);
        return view('admins.configurations.roles.permissions', [
            'modules' => $data->modules,
            'role' => $data->role
        ]);
    }

    public function permissionUpdate($uuid)
    {

        $url_path = "/autorisations/roles/permissionUpdate";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }
}
