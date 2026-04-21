<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  ...$roles  Valores de users.rol permitidos (ej. administrador, recepcionista).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null || ! in_array($user->rol, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'No autorizado.');
        }

        return $next($request);
    }
}
