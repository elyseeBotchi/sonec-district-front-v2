<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpseclib3\File\ASN1\Maps\AccessDescription;

class ControleurConnectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        Log::info(json_encode(AuthConnect()));
        Log::info('controleur');
        if(isset(AuthConnect()['uuid'])){
            if(AuthConnect()['who_is'] =="agent"){
                 $response = $next($request);

                $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
                return $response;
            }else{
                return redirect()->route('panel.login');
            }
        }else{
           // Log::info('*********');
            return redirect()->route('controle.login');
        }

    }
}
