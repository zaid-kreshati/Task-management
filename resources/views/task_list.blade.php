@extends('layouts.header')
@section('title', __('tasks_by_category'))

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('tasks_by_category') }}</title>
</head>
<body>
    <h2 class="text-center mb-4">{{ __('tasks_by_category') }}</h2>

    <!-- Task Status Buttons -->
    <div class="status-buttons text-center mb-4">
        <button class="btn btn-primary status-filter" data-status="all">{{ __('all') }}</button>
        <button class="btn btn-primary status-filter" data-status="{{ App\Enums\TaskStatus::TO_DO->value }}">{{ __('to_do') }}</button>
        <button class="btn btn-primary status-filter" data-status="{{ App\Enums\TaskStatus::IN_PROGRESS->value }}">{{ __('in_progress') }}</button>
        <button class="btn btn-primary status-filter" data-status="{{ App\Enums\TaskStatus::DONE->value }}">{{ __('done') }}</button>
    </div>

    <!-- Horizontal Schedule Container -->
    <div class="schedule-container">
        @include('partials.task_list', ['categories' => $categories, 'userId' => $userId])
    </div>

    <div class="d-flex justify-content-center">
        <div id="pagination-links">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>


<script>

$(document).ready(function() {
    // Handle Task Status Button Click
    $(document).on('click', '.status-filter', function(e) {
        e.preventDefault();

        let status = $(this).data('status');
        loadTasks(status, 1);  // Load tasks for the selected status on the first page
    });

    // Handle Pagination Link Click
    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();

        let page = $(this).attr('href').split('page=')[1];  // Get the page number
        let status = $('.status-filter.active').data('status') || 'all'; // Get the current status filter
        loadTasks(status, page);
    });

    $(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    let pageUrl = $(this).attr('href');

    $.ajax({
    url: pageUrl,
    type: 'GET',
    success: function(response) {
        if (response.html) {
            $('.schedule-container').html(response.html);
            $('.pagination').html(response.pagination); // Update pagination links
        } else {
            alert('{{ __('failed_retrieve_tasks') }}'); // Localized alert
        }
    },
    error: function(xhr) {
        if (xhr.status === 403) {
            alert('{{ __('no_permission_to_view_tasks') }}'); // Localized alert for 403
        } else {
            alert('{{ __('general_error') }}'); // Localized alert for general errors
        }
    }
});

});




    function loadTasks(status, page) {
        $.ajax({
            url: '{{ route("tasks.list", [":status", ":page"]) }}'
                    .replace(':status', status)
                    .replace(':page', page),
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.html && response.pagination) {
                    $('.schedule-container').html(response.html); // Update task list
                    $('#pagination-links').html(response.pagination); // Update pagination links
                } else {
                    alert('Failed to retrieve tasks.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('You do not have permission to view these tasks.');
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    }
});



    $(document).ready(function() {
    // Task Status Button Click
    $(document).on('click', '.status-filter', function(e) {
        e.preventDefault();

        // Get the status from the button's data attribute
        let status = $(this).data('status');

        $.ajax({
            url: '{{ route("tasks.list", ":status") }}'.replace(':status', status),
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.html) {
                    // Update the task container with the received HTML
                    $('.schedule-container').html(response.html);
                } else {
                    alert('Failed to retrieve tasks.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('You do not have permission to view these tasks.');
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
});

</script>
</body>
@endsection

</html>
