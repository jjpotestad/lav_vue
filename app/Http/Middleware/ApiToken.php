<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verifica q el token sea valido
        $token = $request->header('Authorization');
        $valid = User::where('api_token', $token)->first();
        
        if(empty($valid)){
            $data = array(
                'status' => 'error',
                'code' => 401,
                'message' => 'Token incorrecto'
            );
            return response()->json($data,$data['code']);
        }else{ // El token es valido
            return $next($request);
        }
    }
}
