@extends('layouts.header')

@section('title', __('Register'))

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<main class="signup-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h3 class="card text-center">{{ __('Register') }}</h3>
                    <div class="card-body">

                        <form id="registrationForm" action="{{ route('register.user') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="{{ __('Name') }}" id="name" class="form-control" name="name" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="text" placeholder="{{ __('Email') }}" id="email_address" class="form-control" name="email" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="{{ __('Password') }}" id="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="{{ __('Confirm Password') }}" id="password_confirmation" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label for="roleSwitch">{{ __('Register as:') }}</label>
                                <select id="roleSwitch" class="form-select mb-3" name="role" required>
                                    <option value="user">{{ __('User') }}</option>
                                    <option value="leader">{{ __('Leader') }}</option>
                                </select>
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">{{ __('Sign up') }}</button>
                            </div>

                        </form>

                        @if (session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('roleSwitch').addEventListener('change', function() {
        // Change the form action based on the selected role
        if (this.value === 'leader') {
            document.getElementById('registrationForm').action = "{{ route('register.leader') }}";
        } else {
            document.getElementById('registrationForm').action = "{{ route('register.user') }}";
        }
    });
</script>

@endsection
