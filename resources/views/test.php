@extends('layouts.BeeOrder_header')

@section('title', __('categories'))

@section('content')
    <body>
        @csrf

        <!-- Create New Category Button and Form -->
        @role('leader')
            <button id="toggleCreateFormBtn" class="btn btn-primary mb-3">{{ __('create_new_category') }}</button>

            <div id="createCategoryForm" style="display: none;">
                @if (session('success'))
                    <div class="alert alert-success">{{ __('category_created_successfully') }}</div>
                @endif

                <form id="CategoryCreate" action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="category">
                        <label for="name">{{ __('category_name') }}:</label>
                        <input type="text" id="name" name="name" class="category_name" value="{{ old('name') }}">
                    </div>

                    <div class="category">
                        <label for="color">{{ __('category_color') }}:</label>
                        <input type="color" id="color" name="color" class="color-box" value="{{ old('color') }}">
                    </div>

                    @error('name')
                        <div class="alert alert-danger mt-2">{{ __('error_name') }}: {{ $message }}</div>
                    @enderror
                    @error('color')
                        <div class="alert alert-danger mt-2">{{ __('error_color') }}: {{ $message }}</div>
                    @enderror
                    <button class="edit-button">{{ __('create') }}</button>
                </form>
            </div>
        @endrole

        <!-- Search Input Field -->
        <div class="search-container">
            <input type="text" id="searchBox" placeholder="{{ __('search_categories') }}" autocomplete="off">
            <img src="{{ asset('BeeOrder/img/searchIcon.png') }}" alt="{{ __('search_icon_alt') }}">
        </div>

        <!-- Horizontal Schedule Container -->
        <div class="table">
            <h1>{{ __('categories_view') }}</h1>

            @include('partials.categoryIndex', ['categories' => $categories])
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            <div id="pagination-links">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- AJAX and jQuery Script for Creating, Updating, Deleting, and Searching Categories -->
        <script>
            $(function() {
                // Ensure CSRF token is sent with every AJAX request
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Toggle Create Category Form
                $('#toggleCreateFormBtn').click(function() {
                    $('#createCategoryForm').toggle(); // Toggle form visibility
                });

                // Handle Category Creation via AJAX
                $('#CategoryCreate').submit(function(e) {
                    e.preventDefault(); // Prevent form submission

                    let categoryName = $('#name').val();
                    let categoryColor = $('#color').val();

                    $.ajax({
                        url: '{{ route('categories.store') }}',
                        type: 'POST',
                        data: {
                            name: categoryName,
                            color: categoryColor,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('{{ __('category_created_successfully') }}');

                                // Fetch the last page where the new category will be displayed
                                fetchCategories(response.lastPage);

                                // Clear input fields
                                $('#name').val('');
                                $('#color').val('#000000');

                                // Hide the form
                                $('#createCategoryForm').hide();
                            } else {
                                alert('{{ __('failed_to_create_category') }}');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors.name) {
                                    alert('{{ __('error_name') }}: ' + errors.name.join(', '));
                                }
                                if (errors.color) {
                                    alert('{{ __('error_color') }}: ' + errors.color.join(', '));
                                }
                            } else {
                                alert('{{ __('error_occurred') }}');
                            }
                        }
                    });
                });

                // Handle Category Deletion via AJAX
                function deleteCategory(categoryId) {
                    if (confirm('{{ __('confirm_delete_category') }}')) {

                        $.ajax({
                            url: '{{ route('categories.destroy', ':id') }}'.replace(':id', categoryId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}' // CSRF token
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert('{{ __('deleted_successfully') }}');
                                    $(`#category-${categoryId}`).remove(); // Remove category row
                                } else {
                                    alert('{{ __('permission_denied') }}');
                                }
                            },
                            error: function() {
                                alert('{{ __('error_occurred') }}');
                            }
                        });
                    }
                }
            });

            // Handle Pagination Links Click
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                fetchCategories(page);
            });

            function fetchCategories(page) {
                $.ajax({
                    url: '{{ route('categories.index') }}?page=' + page,
                    type: 'GET',
                    success: function(response) {
                        $('#categoriesTable ').html(response.categories); // Update table body with new categories
                        $('#pagination-links').html(response.pagination); // Update pagination links
                    },
                    error: function(xhr) {
                        alert('{{ __('error_occurred') }}');
                    }
                });
            }
        </script>
    </body>
@endsection
</html>

