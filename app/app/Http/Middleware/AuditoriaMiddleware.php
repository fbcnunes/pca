<?php

namespace App\Http\Middleware;

use App\Models\LogAuditoria;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = Auth::user();

        LogAuditoria::create([
            'user_id' => $user?->id,
            'acao' => $request->method().' '.$request->path(),
            'entidade' => $request->route()?->getName() ?? 'route',
            'entidade_id' => null,
            'dados_novos' => $this->sanitizar($request->all()),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }

    protected function sanitizar(array $dados): array
    {
        unset($dados['_token'], $dados['password'], $dados['password_confirmation']);

        return $dados;
    }
}
