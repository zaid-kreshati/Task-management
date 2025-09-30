<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ChecksRoleAndPermission
{
    /**
     * Check if the authenticated user has a specific role and permission.
     *
     * @param string $role The role to check.
     * @param string $permission The permission to check.
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function checkRoleAndPermission(string $role, string $permission)
    {
        // Check if the authenticated user has the specified role and permission
        if (!Auth::user()->hasRole($role) || !Auth::user()->can($permission)) {
            // Return JSON response if the user does not have the required role or permission
            return response()->json(['error' => 'You do not have permission to perform this action.'], 403);
        }

        // Return null if the user has the required role and permission
        return null;
    }
}
