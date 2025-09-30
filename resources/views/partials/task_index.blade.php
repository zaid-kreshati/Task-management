<table id="tasksTable">
    <thead>
        <tr>
            <th>{{ __('ID') }}</th>
            <th>{{ __('Description') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('View Details') }}</th>
            @role('leader')
                <th>{{ __('Delete') }}</th>
            @endrole
        </tr>
    </thead>
    <tbody>
        @forelse($tasks as $task)
            <tr id="task-{{ $task->id }}">
                <td>{{ $task->id }}</td>
                <td>{{ json_decode($task->task_description, true)[app()->getLocale()] }}</td>
                <td>{{ __($task->status->value) }}</td>
                <td class="action-buttons">
                    <a href="{{ route('tasks.details', $task->id) }}" class="btn btn-info">{{ __('View Details') }}</a>
                </td>
                @role('leader')
                    <td>
                        <button class="delete-task-button btn btn-danger" data-id="{{ $task->id }}">{{ __('Delete') }}</button>
                    </td>
                @endrole
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">{{ __('No tasks found.') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    $(function() {
        const searchBox = $('#searchBox');
        const tasksTableBody = $('#tasksTable tbody');
        const tasksTableHead = $('#tasksTable thead');


        searchBox.on('input', function() {
            const query = this.value.toLowerCase().trim();
            const currentPage = "{{ request('page') ?? 1 }}";

            if (query === '') {
                tasksTableBody.empty();
                fetchTasks(currentPage);
            } else {
                $.ajax({
                    url: '/tasks/search',
                    type: 'GET',
                    data: { search: query },
                    success: function(response) {
                        tasksTableBody.empty();

                        if (response.length > 0) {
                            response.forEach(task => {
                                const taskDescription = JSON.parse(task.task_description); // Decode the task description
                                const currentLocale = '{{ app()->getLocale() }}';
                                const localizedName = taskDescription[currentLocale] || taskDescription['en'];

                                const row = `
                                    <tr id="task-${task.id}">
                                        <td>${task.id}</td>
                                        <td>${localizedName}</td>
                                        <td>${task.status}</td>
                                        <td class="action-buttons">
                                            <a href="{{ route('tasks.details', ':id') }}".replace(':id', task.id) class="btn btn-info">{{ __('View Details') }}</a>
                                        </td>
                                        @role('leader')
                                            <td>
                                                <button class="delete-task-button btn btn-danger" data-id="${task.id}">{{ __('Delete') }}</button>
                                            </td>
                                        @endrole
                                    </tr>`;
                                tasksTableBody.append(row);
                            });
                        } else {
                            tasksTableBody.html('<tr><td colspan="5" class="text-center">{{ __('No tasks found.') }}</td></tr>');
                        }
                    },
                    error: function() {
                        alert('{{ __('An error occurred while searching for tasks.') }}');
                    }
                });
            }
        });

        function fetchTasks(page) {
            $.ajax({
                url: '{{ route('tasks.index') }}?page=' + page,
                type: 'GET',
                success: function(response) {
                    tasksTableHead.empty();

                    $('#tasksTable tbody').html(response.tasks); // Update the body of the table
                    $('#pagination-links').html(response.pagination);
                },
                error: function() {
                    alert('{{ __('An error occurred. Please try again.') }}');
                }
            });
        }
    });
</script>
