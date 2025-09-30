<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        // Hash the password before saving
        $data['password'] = Hash::make($data['password']);



        // Create the user using the repository
        $user = $this->userRepository->create($data);



        // Generate a token for the user
        $token = $user->createToken('MyApp')->accessToken;

        // Return the user and token information
        return [
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function login(array $credentials)
    {
        // Attempt to authenticate the user with the given credentials
        if (Auth::attempt($credentials)) {
            // Get the authenticated user
            $user = Auth::user();

            // Generate a new token for the authenticated user
            $token = $user->createToken('Token Name')->accessToken;

            // Return the user and token information
            return [
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
            ];
        } else {
            // Return an error response if authentication fails
            return [
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ];
        }
    }

}
