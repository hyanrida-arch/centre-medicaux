<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vous ajouterez plus tard votre logique ici pour vérifier les rôles
        
        return $next($request);
    }
}