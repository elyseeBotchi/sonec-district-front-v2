<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class SystemeController extends Controller
{
    public function index(){

        return view('admins.configurations.systemes.index');
    }

    public function store(Request $request){
        $url_path = "/autorisations/systemes/store";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'start' => $request->start,
            'end' => $request->end,
            'rate' => $request->rate,
            'total_cumul' => $request->total_cumul,
            'facturation_uuid' => $request->facturation_uuid,

        ];

       // return dd($data);
        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function findAll(Request $request){
        $url_path = "/autorisations/systemes/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

       // return dd($dataResponse);
        return response()->json($dataResponse);
    }

    public function edit($uuid){
        $url_path = "/autorisations/systemes/findOne";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');

        return response()->json($dataResponse);
    }

    public function update(Request $request){
        $url_path = "/autorisations/systemes/update";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'start' => $request->start,
            'end' => $request->end,
            'rate' => $request->rate,
            'total_cumul' => $request->total_cumul,
            'facturation_uuid' => $request->facturation_uuid,
            'uuid' => $request->uuid
        ];

       
        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($dataResponse);
    }

    public function delete($uuid){

        $url_path = "/autorisations/systemes/delete";

        $data = [
            'admin_uuid' => AuthConnect()['uuid'],
            'uuid' => $uuid
        ];

      

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'GET');
       
        //return dd($dataResponse);
        return response()->json($dataResponse);
    }
}
