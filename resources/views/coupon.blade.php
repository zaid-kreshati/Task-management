@extends('layouts.header')

@section('title', __('Update User Coupon'))

@section('content')
    <div class="container">
        <h2>{{ __(' Coupons') }}</h2>

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

        <!-- Coupons Table -->
        <table id="couponTable" class="tasksTable">
            <thead>
                <tr>
                    <th>{{ 'ID' }}</th>
                    <th>{{ 'Code' }}</th>
                    <th>{{ 'Discount (%)' }}</th>
                    <th>{{ 'Is Active' }}</th>
                    <th>{{ 'Update' }}</th>
                    <th>{{ 'Delete' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                <tr id="coupon-{{ $coupon->id }}">
                    <td>{{ $coupon->id }}</td>
                    <td>
                        <input type="text" id="code-{{ $coupon->id }}" class="form-control code" value="{{ $coupon->code }}">
                    </td>
                    <td>
                        <input type="number" id="discount-{{ $coupon->id }}" class="form-control discount" value="{{ $coupon->discount }}" step="0.1" min="0" max="100">
                    </td>

                    <td>
                           <input type="text" id="activate" class="form-control discount" value="{{ $coupon->is_active ? 'Active' : 'Inactive' }}" >
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary update-coupon-btn" data-id="{{ $coupon->id }}">
                            {{ __('Update') }}
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger delete-coupon-btn" data-id="{{ $coupon->id }}">
                            {{ __('Delete') }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Create Coupon Form -->
        <h3>{{ __('Create New Coupon') }}</h3>
        <form id="createCouponForm">
            <div class="form-group">
                <label for="new-code">Code</label>
                <input type="text" id="new-code" name="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new-discount">Discount (%)</label>
                <input type="number" id="new-discount" name="discount" class="form-control" required>
            </div>

            <button type="button" id="create-coupon-btn" class="btn btn-success mt-2">{{ __('Create Coupon') }}</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            const couponTableBody = $('#couponTable tbody');

            // Update coupon on button click
            $('.update-coupon-btn').click(function() {
                let id = $(this).data('id');
                let code = $('#code-' + id).val();
                let discount = $('#discount-' + id).val();
                let is_active = $('#activate').val();

                updateCoupon(id, code, discount, is_active);
            });

            // Update coupon function
            function updateCoupon(id, code, discount, is_active) {
                $.ajax({
                    url: '{{ route('coupons.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        code: code,
                        discount: discount,
                        is_active : is_active,
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('{{ __('An error occurred while updating the coupon.') }}');
                    }
                });
            }

            // Create new coupon on button click
            $('#create-coupon-btn').click(function() {
                let code = $('#new-code').val();
                let discount = $('#new-discount').val();

                $.ajax({
                    url: '{{ route('coupons.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        code: code,
                        discount: discount,
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);

                            // Append the new coupon to the table
                            const newRow = `
                                <tr id="coupon-${response.coupon.id}">
                                    <td>${response.coupon.id}</td>
                                    <td>
                                        <input type="text" id="code-${response.coupon.id}" class="form-control code" value="${response.coupon.code}">
                                    </td>
                                    <td>
                                        <input type="number" id="discount-${response.coupon.id}" class="form-control discount" value="${response.coupon.discount}" step="0.1" min="0" max="100">
                                    </td>
                                    <td>{{'active'}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary update-coupon-btn" data-id="${response.coupon.id}">
                                            {{ __('Update') }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger delete-coupon-btn" data-id="${response.coupon.id}">
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                </tr>
                            `;
                            couponTableBody.append(newRow);  // Append the new row to the table
                            $('#new-code').val('');
                            $('#new-discount').val('');

                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('{{ __('An error occurred while creating the coupon.') }}');
                    }
                });
            });

            // Delete coupon on button click
            $('.delete-coupon-btn').click(function() {
                let id = $(this).data('id');

                if (confirm('{{ __('Are you sure you want to delete this coupon?') }}')) {
                    deleteCoupon(id);
                }
            });

            // Delete coupon function
            function deleteCoupon(id) {
                $.ajax({
                    url: '{{ route('coupons.delete') }}',  // Update with the correct route for deletion
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#coupon-' + id).remove();  // Remove the coupon row from the table
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('{{ __('An error occurred while deleting the coupon.') }}');
                    }
                });
            }
        });
    </script>
@endsection
