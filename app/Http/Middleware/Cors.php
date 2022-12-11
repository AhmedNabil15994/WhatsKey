<?php namespace App\Http\Middleware;

use Closure;

class Cors
{

    public function handle($request, Closure $next){
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application,Access-Control-Request-Headers');

        return $response;
        // return $next($request)
        // ->header('Access-Control-Allow-Origin', '*')
        // ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        // ->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
    }
}
