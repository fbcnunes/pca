<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissoes): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Usuário não autenticado');
        }

        if (empty($permissoes)) {
            return $next($request);
        }

        foreach ($permissoes as $permissao) {
            if ($user->temPermissao($permissao)) {
                return $next($request);
            }
        }

        abort(403, 'Permissão negada');
    }
}
