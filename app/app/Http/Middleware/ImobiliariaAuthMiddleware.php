<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ImobiliariaAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado no guard 'imobiliaria'
        // NOTA: Assumindo que o guard 'imobiliaria' foi configurado no auth.php
        if (!Auth::guard('imobiliaria')->check()) {
            return redirect()->route('login')->with('error', 'Acesso restrito a imobiliárias parceiras.');
        }

        // Restrição absoluta: A imobiliária não pode acessar rotas de administração global
        if ($request->is('admin/*')) {
            abort(403, 'Acesso negado. Você não possui permissões administrativas.');
        }

        return $next($request);
    }
}
