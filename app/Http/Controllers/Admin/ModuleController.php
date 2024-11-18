<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(){

        return view('admins.configurations.modules.index');
    }

    public function store(Request $request){
        $url_path = "/autorisations/modules/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name,
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function findAll(Request $request){
        $url_path = "/autorisations/modules/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function edit($uuid){
        $url_path = "/autorisations/modules/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function update(Request $request){
        $url_path = "/autorisations/modules/update";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'name' => $request->name,
            'uuid' => $request->uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function delete($uuid){

        $url_path = "/autorisations/modules/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }
}
