<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\registerRequest;
use App\Http\Requests\Auth\loginRequest;

class AuthController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('register');
    }


    public function register_leader(registerRequest $request)
    {
        // Check if the authenticated user has the 'leader' role
        if (auth()->user()->hasRole('leader')) {


            // Extract the user data from the request
            $data = $request->only(['name', 'email', 'password']);

            // Call the AuthService to register the user
            $result = $this->authService->register($data);
            $user = $result['user']; // Assuming the registered user is returned in 'user' key

            // Assign the 'leader' role to the newly registered user
            $user->assignRole('leader');

            // Check if the registration was successful
            if ($result['status'] === 'success') {
                return redirect()->route('login')->with('success', 'Registration successful. Welcome!');
            } else {
                return redirect()->back()->with('error', 'Registration failed. Please try again.');
            }
        } else {
            // Redirect back with an error message if the user does not have permission
            return redirect()->back()->with('error', 'You do not have permission to register leaders.');
        }
    }

    public function register_user(registerRequest $request)
    {

        // Extract the user data from the request
        $data = $request->only(['name', 'email', 'password']);

        // Call the AuthService to register the user
        $result = $this->authService->register($data);
        $user = $result['user']; // Assuming the registered user is returned in 'user' key

        // Assign the 'user' role to the newly registered user
        $user->assignRole('user');

        // Check if the registration was successful
        if ($result['status'] === 'success') {
            return redirect()->route('login')->with('success', 'Registration successful. Welcome!');
        } else {
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }


    public function showLoginForm()
    {
        return view('login');
    }

    public function login(loginRequest $request)
    {

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->intended(route('home'));
        }

                        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
         Auth::logout();
        return view('welcome');

    }
}
