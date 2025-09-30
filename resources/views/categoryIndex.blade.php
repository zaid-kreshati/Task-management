@extends('layouts.BeeOrder_header')

@section('title', 'Categories')

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
                        <label for="name_en">{{ __('Category Name (English)') }}:</label>
                        <input type="text" id="name_en" name="name_en" class="category_name" value="{{ old('name_en') }}">
                    </div>

                    <div class="category">
                        <label for="name_ar">{{ __('Category Name (Arabic)') }}:</label>
                        <input type="text" id="name_ar" name="name_ar" class="category_name" value="{{ old('name_ar') }}">
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
        <div>
            <h1>{{ __('categories_view') }}</h1>

            @include('partials.categoryIndex', ['categories' => $categories])
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            <div id="pagination-links">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </body>


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

           let categoryNameEn = $('#name_en').val();
           let categoryNameAr = $('#name_ar').val();
           let categoryColor = $('#color').val();

           $.ajax({
               url: '{{ route('categories.store') }}',
               type: 'POST',
               data: {
                   name_en: categoryNameEn,   // English name
                   name_ar: categoryNameAr,   // Arabic name
                   color: categoryColor,
                   _token: '{{ csrf_token() }}' // CSRF token for security
               },
               success: function(response) {
                   if (response.success) {
                       alert('{{ __('category_created_successfully') }}');
                       const categoriesTableHead = $('#categoriesTable thead');
                       categoriesTableHead.empty();


                       fetchCategory(response.lastPage);

                       $('#name_en').val('');
                       $('#name_ar').val('');
                       $('#color').val('#000000');
                       $('#createCategoryForm').hide();
                   } else {
                       alert('{{ __('failed_to_create_category') }}');
                   }
               },
               error: function(xhr) {
                   if (xhr.status === 422) {
                       const errors = xhr.responseJSON.errors;
                       if (errors.name_en) {
                           alert('English Name Error: ' + errors.name_en.join(', '));
                       }
                       if (errors.name_ar) {
                           alert('Arabic Name Error: ' + errors.name_ar.join(', '));
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

       // Handle Category Update via AJAX
       function updateCategory(categoryId, categoryName, categoryColor) {
           $.ajax({
               url: '{{ route('categories.update', ':id') }}'.replace(':id', categoryId),
               type: 'PUT',
               data: {
                   name: categoryName,
                   color: categoryColor,
                   _token: '{{ csrf_token() }}' // CSRF token
               },
               success: function(response) {
                   alert('{{ __('category_updated_successfully') }}');
               },
               error: function(xhr) {
                   alert('An error occurred. Please try again.');
               }
           });
       }

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

       // Update Category Button Click
       $(document).on('click', '.update-category-button', function(e) {
           e.preventDefault();
           let categoryId = $(this).data('id');
           let categoryRow = $(this).closest('tr');
           let categoryName = categoryRow.find('input[name="name"]').val();
           let categoryColor = categoryRow.find('input[name="color"]').val();
           updateCategory(categoryId, categoryName, categoryColor);
       });

       // Delete Category Button Click
       $(document).on('click', '.delete-category-button', function(e) {
           e.preventDefault();
           let categoryId = $(this).data('id');
           deleteCategory(categoryId);
       });

       // Handle Pagination Links Click
       $(document).on('click', '.pagination a', function(e) {
           e.preventDefault();
           const page = $(this).attr('href').split('page=')[1];


           fetchCategory(page);
       });

       // Search Box Input Event
       const searchBox = $('#searchBox');
       const categoriesTableBody = $('#categoriesTable tbody');
       const categoriesTableHead = $('#categoriesTable thead');


       searchBox.on('input', function() {
           const query = this.value.toLowerCase().trim();
           const currentPage = "{{ request('page') ?? 1 }}";



           categoriesTableBody.empty();
           if (query === '') {
            categoriesTableBody.empty();
            categoriesTableHead.empty();

             fetchCategory(currentPage);
           } else {
               $.ajax({
                   url: '/categories/search',
                   type: 'GET',
                   data: { search: query },
                   success: function(response) {
                       if (response.length > 0) {
                           response.forEach(category => {
                               const categoryName = category.name;
                               const currentLocale = '{{ app()->getLocale() }}';
                               const localizedName = categoryName[currentLocale] || categoryName['en'];

                               let row;
                               if ('{{ auth()->user()->hasRole('leader') }}') {

                                   row = `
                                       <tr id="category-${category.id}">
                                           <td>${category.id}</td>
                                           <td><input type="text" name="name" value="${localizedName}" class="form-control" required></td>
                                           <td><input type="color" name="color" value="${category.color}" class="color-box" required></td>
                                           <td><button class="update-category-button edit-button" data-id="${category.id}">Update</button></td>
                                           <td><button class="delete-category-button edit-button" data-id="${category.id}">Delete</button></td>
                                       </tr>
                                   `;
                               } else {
                                   row = `
                                       <tr id="category-${category.id}">
                                           <td>${category.id}</td>
                                           <td>${localizedName}</td>
                                           <td><div class="color-square" style="background-color: ${category.color};"></div></td>
                                       </tr>
                                   `;
                               }
                               categoriesTableBody.append(row); // Append rows to the body
                           });
                       } else {
                           categoriesTableBody.html('<tr><td colspan="5">No categories found.</td></tr>');
                       }
                   },
                   error: function() {
                       alert('An error occurred while searching. Please try again.');
                   }
               });
           }
       });

       // Function to fetch categories for a specific page
       function fetchCategory(page) {
           $.ajax({
               url: '{{ route('categories.index') }}?page=' + page,
               type: 'GET',
               success: function(response) {
                   $('#categoriesTable tbody').html(response.categories);  // Only update the tbody

                   $('#pagination-links').html(response.pagination);  // Update pagination links
               },
               error: function() {
                   alert('An error occurred while fetching categories. Please try again.');
               }
           });
       }

       });

       </script>



@endsection
