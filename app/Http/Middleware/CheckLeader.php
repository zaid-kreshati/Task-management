<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\JsonResponseTrait; // Include the trait


class CheckLeader
{
    use JsonResponseTrait; // Use the JsonResponseTrait

    public function handle($request, Closure $next)
    {
        // Use 'api' guard for Passport (or change to your custom guard if needed)
        if (Auth::guard('api')->check()) {
            // Check if the user has the 'leader' role
            if (Auth::guard('api')->user()->hasRole('leader')) {
                return $next($request);
            }
        }

        // Return unauthorized response if the user is not a leader
        return $this->errorResponse('Unauthorized ',401);

        //return response()->json(['error' => 'Unauthorized. Leader role required.'], 403);
    }
}
