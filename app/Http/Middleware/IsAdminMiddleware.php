<?php

namespace App\Http\Middleware;

use Closure;

class IsAdminMiddleware
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

        if(auth()->user() && auth()->user()->isAdmin == 1){
            
            // return 33;
            return $next($request);
        }

        return response()->json([
            'msg' => 'Forbidden! You don\'t have admin access', 
            'errCode' => 403 
        ]);
    }
}
