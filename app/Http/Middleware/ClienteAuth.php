<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ClienteAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('cliente_logueado')) {
            return redirect()->route('home')->with('error', 'Debes iniciar sesión como cliente para acceder a esta zona.');
        }

        return $next($request);
    }
}
