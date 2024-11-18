<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index($uuid){

        $url_path = "/autorisations/modules/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid,
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');
           // return dd($dataResponse);
        return view('admins.configurations.modules.permissions.index', [
            'module' => json_decode(json_encode($dataResponse['data']))
        ]);
    }

    public function store(Request $request, $module_uuid){
        $url_path = "/autorisations/modules/permissions/" . $module_uuid . "/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }
    public function findAll(Request $request, $module_uuid){
        $url_path = "/autorisations/modules/permissions/" . $module_uuid . "/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $module_uuid ?? ''
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function edit($uuid){
        $url_path = "/autorisations/modules/permissions/" . $uuid . "/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid,
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function update(Request $request, $uuid){
        $url_path = "/autorisations/modules/permissions/".$uuid."/update";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name,
            'uuid' => $uuid,
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function delete($uuid){

        $url_path = "/autorisations/modules/permissions/".$uuid."/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }
}
