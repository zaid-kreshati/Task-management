@extends('layouts.BeeOrder_header')

@section('title', __('login'))

@section('content')


<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card text-center">{{ __('Login') }}</h3>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="{{ __('Email') }}" id="email" class="form-control" name="email" required autofocus>
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="{{ __('Password') }}" id="password" class="form-control" name="password" required>
                                @if ($errors->has('emailPassword'))
                                    <span class="text-danger">{{ $errors->first('emailPassword') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">{{ __('Signin') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div>
                    <a href="{{ route('register.form') }}">{{ __('Signup') }}</a>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
