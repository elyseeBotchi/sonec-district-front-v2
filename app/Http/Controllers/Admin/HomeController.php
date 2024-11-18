<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorSVG;
use function App\Http\Controllers\AuthConnect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {
        $this->middleware('IsConnect');
        /*  if(isset(AuthConnect()['auth_profil'])){
              if(AuthConnect()['auth_profil']=="candidat"){
                  return redirect()->route('espace.candidat.home');
              }
          }*/
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admins.index');
    }


}
