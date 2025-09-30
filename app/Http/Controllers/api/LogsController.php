<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Logs;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;


class LogsController extends Controller_api
{
    use JsonResponseTrait; // Use the trait for JSON responses

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function logs()
    {
        // Check if user has leader role using Spatie role check
        if (!auth()->user()->hasRole('leader')) {
            // Return JSON response if the user does not have the required role or permission
            return $this->errorResponse('you do not have permission');
        }

        $logs = Logs::orderBy('created_at', 'desc')->get();

        // Return the logs as a JSON response
        return $this->successResponse($logs, 'Logs fetched successfully.');

    }
}
