<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NivelPermissaoGerencia
{
    private $niveis = [
        1 => [
            'grupos.index' => true,
            'grupos.store' => false,
            'grupos.update' => false,
            'grupos.show' => true,
            'grupos.destroy' => false,
            'clientes.index' => true,
            'clientes.store' => true,
            'clientes.update' => true,
            'clientes.destroy' => true,
            'gerentes.index' => true,
            'gerentes.store' => true,
            'gerentes.update' => true,
            'gerentes.destroy' => true
        ],
        2 =>  [
            'grupos.index' => true,
            'grupos.store' => true,
            'grupos.update' => true,
            'grupos.show' => true,
            'grupos.destroy' => true,
            'clientes.index' => true,
            'clientes.store' => true,
            'clientes.update' => true,
            'clientes.destroy' => true,
            'gerentes.index' => true,
            'gerentes.store' => true,
            'gerentes.update' => true,
            'gerentes.destroy' => true
        ]
    ];

    private function verificar($usuario, $next, $request)
    {
        $recurso =  $request->route()->getAction()['as'];

        $permissao = $this->niveis[$usuario->nivel][$recurso];

        if ($permissao) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Desculpe, você não tem permissão para acessar esse recurso!',
        ], 401);
    }

    public function handle(Request $request, Closure $next)
    {
        return $this->verificar(Auth::user(), $next, $request);
    }
}
