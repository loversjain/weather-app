<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\RolesEnum;
/**
 * Class CheckUserRole
 *
 * Middleware for checking user roles.
 *
 * @package App\Http\Middleware
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request The incoming HTTP request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next The next middleware closure.
     * @return \Symfony\Component\HttpFoundation\Response Returns the response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

          // Check if user is authenticated and has required role
        if (!$user || !in_array($user->role, $this->getAllowedRoles())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent access to admin URLs for non-admin users
        if ($user->role == RolesEnum::Buyer->value && $request->is('admin/*')) {
            // Redirect unauthorized users to a different route
            return response()->json(['error' => 'Unauthorized! You are not authorized to access this page.']);
        }

        // Prevent access to buyer URLs for non-buyer users
        if ($user->role == RolesEnum::Admin->value && $request->is('buyer/*')) {
            // Redirect unauthorized users to a different route
            return response()->json(['error' => 'Unauthorized! You are not authorized to access this page.']);
        }
        return $next($request);

        
    }

    /**
     * Get the allowed roles.
     *
     * @return array Returns an array of allowed roles.
     */
    private function getAllowedRoles(): array
    {
        // Define allowed roles here
        return [RolesEnum::Admin->value, RolesEnum::Buyer->value]; //put new role 
    }
}
