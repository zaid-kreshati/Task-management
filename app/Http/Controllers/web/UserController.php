<?php

namespace App\Http\Controllers\web;

use App\Http\Requests\ValidateUserRequest;
use App\Models\User;
use App\Services\UserService;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function store(ValidateUserRequest $request)
    {

        $data = $request->only(['name', 'email', 'password']);
        $user = $this->userService->createUser($data);

        // return response()->json($user, 201);

        return response()->json([
            'status' => 'user created successfully ',
            'user' => $user,
        ],201);

    }
}
