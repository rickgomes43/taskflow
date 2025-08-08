<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $roles,
                'route' => $request->route()->getName() ?? $request->path(),
                'ip' => $request->ip(),
            ]);

            // Redirect based on user's role with appropriate message
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard')
                    ->with('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
            } elseif ($user->isCollaborator()) {
                return redirect('/dashboard')
                    ->with('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
            } else {
                return redirect('/dashboard')
                    ->with('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
            }
        }

        return $next($request);
    }
}