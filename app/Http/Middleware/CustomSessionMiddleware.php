<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class CustomSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // Definir o ID de sessão personalizado
        $customSessionId = 'sessao'; // Substitua isso pelo ID de sessão desejado

        // Definir o ID da sessão no Laravel
        Session::setId($customSessionId);

        // Iniciar a sessão
        Session::start();

        return $next($request);
    }
}
