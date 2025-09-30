<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\TaskNotFoundException;





class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
{
    if ($exception instanceof ValidationException) {
        return $this->errorResponse('Validation failed2');

        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Validation failed',
        //     'errors' => $exception->errors()
        // ], 422);
    }

    if ($exception instanceof TaskNotFoundException) {
        return $this->errorResponse('Task Not Found2');

        //return $exception->render($request);
    }

    // if ($exception instanceof ModelNotFoundException) {
    //     return response()->json([
    //         'error' => 'Resource not found'
    //     ], 404);
    // }



    return parent::render($request, $exception);
}




    protected function invalidJson($request, ValidationException $exception)
{
    return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $exception->errors(),
    ], 422);
}

public function unauthenticated($request, AuthenticationException $exception)
{
    // Return a JSON response for API requests
    if ($request->expectsJson()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Token is invalid or expired.',
        ], 401);
    }

    // Fallback to default behavior (usually a redirect for web requests)
    return redirect()->guest(route('login'));
}

}
