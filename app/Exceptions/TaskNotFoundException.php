<?php

namespace App\Exceptions;

use Exception;
use App\Traits\JsonResponseTrait; // Include the trait


class TaskNotFoundException extends Exception
{
    use JsonResponseTrait; // Use the JsonResponseTrait

    // Define a default message for the exception
    protected $message = 'Task not found.';

    // Optionally, override the constructor to pass custom messages
    public function __construct($message = null)
    {
        parent::__construct($message ?? $this->message);
    }

    // Define how the exception should render the response
    public function render($request)
    {
        return $this->errorResponse('Task Not Found',404);

    }
}
