<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class SupportsController extends Controller
{

    public function index($entity_uuid)
    {
       // return dd($entity_uuid);
        return view('admins.support.index', [
                'Entity_uuid'=>$entity_uuid ?? '',
        ]);
      
    }    
    
    public function search(Request $request)
    {
       // return dd($entity_uuid);
        return view('admins.support.index', [
                'Entity_uuid'=>$entity_uuid ?? '',
        ]);
      
    }

}
