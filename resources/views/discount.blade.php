@extends('layouts.header')

@section('title', __('Update User Discount'))

@section('content')
    <div class="container">
        <h2>{{ __('User`s Discount') }}</h2>

        <!-- Flash message for success or error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- User info -->
        <table id="tasksTable" class="tasksTable  ">
            <thead>
                <tr>
                    <th>{{ 'ID' }}</th>
                    <th>{{ 'Email' }}</th>
                    <th>{{ 'Discount' }}</th>
                    <th>{{ 'Update Discount' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr id="user-{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->email }}</td>
                    <td id="discount-{{ $user->id }}">{{ $user->discount ?? 0 }}%</td>
                    <td>
                        <input type="number" id="discount-input-{{ $user->id }}" class="form-control discount-input" value="{{ $user->discount ?? 0 }}" min="0" max="100" step="0.01">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script>
        $(document).ready(function() {
            // Update discount on Enter key press
            $('.discount-input').on('keypress', function(e) {
                if (e.which === 13) {
                    let userId = $(this).closest('tr').attr('id').split('-')[1];
                    let discount = $(this).val();
                    updateDiscount(userId, discount);
                }
            });

            // Update discount function
            function updateDiscount(userId, discount) {
                $.ajax({
                    url: '{{ route('discount.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: userId,
                        discount: discount
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#discount-' + userId).text(discount + '%'); // Update the discount value in the table
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('{{ __('An error occurred while updating the discount.') }}');
                    }
                });
            }

        });
    </script>
@endsection
