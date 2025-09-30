<!DOCTYPE html>

<html lang="{{ app()->currentLocale() }}">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


@extends('layouts.BeeOrder_header')

@section('title', __('welcome'))

@section('content')
<body>
    <!-- Main Content -->
        <div class="logo mb-4">
            <img src="{{ asset('BeeOrder/img/beeOrder.png') }}" alt="Logo">
        </div>
        <h1>{{ __('welcome') }}</h1>
        <h2>{{ __('BeeOrder Task Management') }}</h2>
        <div class="btn-group mt-4">
            <a href="{{ url('/register') }}" class="btn btn-primary">{{ __('Register') }}</a>
            <a href="{{ url('/login') }}" class="btn btn-secondary">{{ __('Login') }}</a>
        </div>
@endsection
</body>
</html>
