<!DOCTYPE html>
<html lang="{{ app()->currentLocale() }}">
@extends('layouts.header')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @section('title', __('Task Management'))
</head>

<body>
    <div class="image-container">
        <img src="/img/taskmanager.webp" alt="{{ __('task manager') }}">
    </div>

    <div class="main-content">
        <div class="btn-group">
            <a href="{{ route('tasks.index') }}" class="btn">{{ __('View All Tasks') }}</a>
            <a href="{{ route('categories.index') }}" class="btn">{{ __('View All Categories') }}</a>
            <a href="{{ route('tasks.list', ['status' => 'all']) }}" class="btn">{{ __('Tasks List') }}</a>
            @role('leader')
            <a href="{{ route('discount') }}" class="btn">{{ __('discount') }}</a>
            <a href="{{ route('coupon') }}" class="btn">{{ __('coupon') }}</a>

            @endrole

        </div>
    </div>

@endsection

</body>
</html>
