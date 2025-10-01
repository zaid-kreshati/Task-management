@extends('layouts.header')
@section('title', __('Tasks'))
@section('content')
    <body>
        @csrf
        @role('leader')
            <button id="toggleFormButton" class="btn btn-primary mb-3">{{ __('Create New Task') }}</button>

            <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" style="display: none;">
                @csrf

                <div class="coupon">
                    <div class="task-list2">

                        <form id="VoucherForm" action="{{ route('voucher.discount') }}" method="POST">
                            @csrf

                            <div class="schedule-item">
                                <label for="code">Code:</label>
                                <input type="text" id="code" name="code" class="form-control" required>
                            </div>
                            <button type="button" id="Voucher" class="btn btn-primary mb-3">Voucher</button>
                        </form>

                        <!-- Display discounted price -->
                        <div id="discountedPrice" style="margin-top: 20px;">
                            <p>{{ __('Price after discount:') }} <span id="priceValue">{{ getPrice()/100 }}$</span></p>
                        </div>

                    </div>
                    <div class="task-list2">
                        <!-- Task description in English -->
                        <div class="schedule-item">
                            <label for="task_description_en">{{ __('Task Description (English)') }}:</label>
                            <input type="text" id="task_description_en" name="task_description_en" class="form-control"
                                value="{{ old('task_description_en') }}">
                        </div>

                        <!-- Task description in Arabic -->
                        <div class="schedule-item">
                            <label for="task_description_ar">{{ __('Task Description (Arabic)') }}:</label>
                            <input type="text" id="task_description_ar" name="task_description_ar" class="form-control"
                                value="{{ old('task_description_ar') }}">
                        </div>

                        <!-- Task deadline -->
                        <div class="schedule-item">
                            <label for="dead_line">{{ __('Deadline') }} :</label>
                            <input type="datetime-local" class="form-control" id="dead_line" name="dead_line" required>
                        </div>

                        <!-- Assign Users -->
                        <label class="schedule-item" for="assignUsersDropdown">{{ __('Assign Users') }}:</label>
                        <select id="assignUsersDropdown" name="assign_users[]" multiple required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>

                        <!-- Assign Categories -->
                        <label class="schedule-item" for="assignCategoriesDropdown">{{ __('Assign Categories') }}:</label>
                        <select id="assignCategoriesDropdown" class="form-control" name="assign_categories[]" multiple>
                            @foreach ($Categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                         <button type="button" id="proceedToPayment"
                        class="btn btn-primary" style="margin: 20px">{{ __('Proceed to Payment') }}</button>
                    </div>



                </div>

            </form>
        @endrole

        <div class="container">
            <div class="search-container">
                <input type="text" id="searchBox" placeholder="{{ __('Search tasks') }}" autocomplete="on">
                <img src="{{ asset('img/searchIcon.png') }}" alt="{{ __('Search Icon') }}">
            </div>

            <h1>{{ __('Tasks View') }}</h1>
            @include('partials.task_index', compact('tasks', 'users', 'Categories'))

            <div class="d-flex justify-content-center">
                <div id="pagination-links">
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>


        <script>
            $(function() {
                let finalPrice = {{ getPrice() }};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Initialize Select2 for dropdowns
                $('#assignUsersDropdown').select2();
                $('#assignCategoriesDropdown').select2();

                // Maintain a global array for tasks
                let tasks = @json($tasks);

                // Toggle form visibility
                $('#toggleFormButton').click(function() {
                    $('#taskForm').toggle();
                });

                // Handle task creation and payment
                $('#proceedToPayment').on('click', function() {
                    var formData = $('#taskForm').serialize();

                    $.ajax({
                        url: $('#taskForm').attr('action'),
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                alert(response.newPrice);
                                window.location.href =
                                    "{{ route('create-checkout-session') }}?price=" + finalPrice;
                            } else {
                                alert('{{ __('Failed to create the task.') }}');
                            }
                        },
                        error: function() {
                            alert('{{ __('An error occurred. Please try again.') }}');
                        }
                    });
                });


                // Handle Pagination Links Click
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    const page = $(this).attr('href').split('page=')[1];


                    fetchTasks(page);
                });

                function fetchTasks(page, callback) {
                    $.ajax({
                        url: '{{ route('tasks.index') }}?page=' + page,
                        type: 'GET',
                        success: function(response) {
                            $('#tasksTable').html(response.tasks); // Update task list
                            $('#pagination-links').html(response.pagination); // Update pagination

                            if (typeof callback === 'function') {
                                callback();
                            }
                        },
                        error: function(xhr) {
                            alert('{{ __('An error occurred. Please try again.') }}');
                        }
                    });
                }

                // Handle task deletion
                $(document).on('click', '.delete-task-button', function(e) {
                    e.preventDefault();
                    let taskId = $(this).data('id');

                    if (confirm('{{ __('Are you sure you want to delete this task?') }}')) {
                        $.ajax({
                            url: '{{ route('tasks.destroy', ':id') }}'.replace(':id', taskId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#task-' + taskId).remove();
                                    alert(response.message);
                                } else {
                                    alert('{{ __('Failed to delete the task.') }}');
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 403) {
                                    alert(
                                        '{{ __('You do not have permission to delete this task.') }}');
                                } else {
                                    alert('{{ __('An error occurred. Please try again.') }}');
                                }
                            }
                        });
                    }
                });

                $('#Voucher').on('click', function(e) {
                    e.preventDefault();

                    let code = $('#code').val();

                    $.ajax({
                        url: `/voucher/discount`,
                        method: 'POST',
                        data: {
                            code: code,
                            _token: $('input[name="_token"]').val(),
                        },
                        success: function(response) {
                            if (response.success) {
                                finalPrice = response.newPrice; // Update with the discounted price
                                $('#priceValue').text(finalPrice); // Show updated price in view
                                alert('discount successful'); // Show error message

                            } else {
                                alert(response.message); // Show error message
                            }
                        },
                        error: function() {
                            alert('{{ __('An error occurred. Please try again.') }}');
                        }
                    });
                });
            });
        </script>
    @endsection
</body>
</html>
