<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // setting the token (you can check it in .env)
        $expectedToken = env('TOKEN_CHECK', 'artificially-token');

        // geet the authorization header
        $authHeader = $request->header('Authorization');

        //check if auth is set
        if(!$authHeader){
            abort(403, 'Unauthorized');
        }
       
        // Simple check for Bearer token format and validity
        if (!str_starts_with($authHeader, 'Bearer ')) {
            abort(403,'Unauthorized: Invalid Auth Type');
        }

        // extract the token and compare it with the expected token
        $token = substr($authHeader, 7); 
        if ($token !== $expectedToken) {
            abort(403, 'Unauthorized: Invalid Bearer token.');
        }

        return $next($request);
    }
}
