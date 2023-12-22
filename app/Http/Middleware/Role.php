<?php

namespace App\Http\Middleware;

use App\Enums\Role as RoleType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $role): Response
    {
        if ($user = auth()->user()) {
            if ($role === RoleType::ADMIN->value && $user->role === RoleType::ADMIN->value)
                return $next($request);
            elseif ($role === RoleType::MEMBER->value && $user->role === RoleType::MEMBER->value)
                return $next($request);
        }

        throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('');
    }
}
