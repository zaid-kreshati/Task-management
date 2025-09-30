@php
    use App\Enums\TaskStatus; // Declare the TaskStatus enum here
@endphp

<h1>{{ __('Subtasks') }}</h1>


@hasrole('leader')
            <!-- Form to Add Subtask -->
            <div class="form-group">
                <h3>{{ __('Add New Subtask') }}</h3>
                <form id="addSubtaskForm">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <label>{{ __('Subtask Name (English)') }}</label>
        <input type="text" id="subtask_name_en" name="name_en" class="form-control" placeholder="{{ __('Subtask Name (English)') }}">

        <label>{{ __('Subtask Name (Arabic)') }}</label>
        <input type="text" id="subtask_name_ar" name="name_ar" class="form-control" placeholder="{{ __('Subtask Name (Arabic)') }}">

        <button type="submit" class="btn btn-primary mt-2">{{ __('Add Subtask') }}</button>

                </form>
            </div>

            <!-- Subtasks Table -->
            @if ($Subtasks)
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('update') }}</th>
                            <th>{{ __('Delete') }}</th>
                            <th>{{ __('Comments') }}</th>
                        </tr>
                    </thead>
                    <tbody id="subtasksTableBody">
                        @forelse($Subtasks as $subtask)
                        <form class="update-subtask-form" data-id="{{ $subtask->id }}">

                            <tr id="subtask-{{ $subtask->id }}">
                                <td>{{ $subtask->id }}</td>
                                <td>
                                    <input type="text" class="form-control subtask-name" name="name" value="{{ json_decode($subtask->name, true)[app()->getLocale()] }}">
                                </td>
                                <td>
                                    <!-- SubTask Status Dropdown -->
                                    <select name="subtask_status">
                                        <option value="{{ TaskStatus::TO_DO->value }}" {{ $subtask->status === TaskStatus::TO_DO->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::TO_DO->value) }}
                                        </option>
                                        <option value="{{ TaskStatus::IN_PROGRESS->value }}" {{ $subtask->status === TaskStatus::IN_PROGRESS->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::IN_PROGRESS->value) }}
                                        </option>
                                        <option value="{{ TaskStatus::DONE->value }}" {{ $subtask->status === TaskStatus::DONE->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::DONE->value) }}
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <button class="update-subtask-btn btn btn-success" data-id="{{ $subtask->id }}">{{ __('update') }}</button>
                                </td>
                                <td>
                                    <button class="delete-subtask-btn btn btn-danger" data-id="{{ $subtask->id }}">{{ __('Delete') }}</button>
                                </td>
                                <td class="action-buttons">
                                    <a href="{{ route('comments.index', ['type' => 'Subtask', 'id' => $subtask->id]) }}" class="btn btn-info">{{ __('Comments') }}</a>
                                </td>
                            </tr>
                        </form>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('No subtasks found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <p>{{ __('No subtasks available.') }}</p>
            @endif
        @else
            <!-- Read-only Subtasks Table for Non-Leaders -->
            @if ($Subtasks)
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Comments') }}</th>
                        </tr>
                    </thead>
                    <tbody id="subtasksTableBody">
                        @forelse($Subtasks as $subtask)
                        <tr id="subtask-{{ $subtask->id }}">
                            <td>{{ $subtask->id }}</td>
                            <td>
                                <span>{{ json_decode($subtask->name, true)[app()->getLocale()] }}</span>
                            </td>
                            <td>
                                {{ __($subtask->status->value) }}
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('comments.index', ['type' => 'Subtask', 'id' => $subtask->id]) }}" class="btn btn-info">{{ __('Comments') }}</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('No subtasks found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <p>{{ __('No subtasks available.') }}</p>
            @endif
        @endrole
    </div>
    <script>
        $(document).ready(function(){
           // Handle form submission to add a subtask
            $('#addSubtaskForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = {
            task_id: $('input[name="task_id"]').val(),
            name_ar: $('#subtask_name_ar').val(), // Arabic name
            name_en: $('#subtask_name_en').val(), // English name
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route('subtasks.store') }}',
            type: 'POST',
            data: formData,
            success: function(response) {

                   // Assume response.subtask.name is a JSON string that contains both name_ar and name_en
                   const name = JSON.parse(response.subtask.name); // Parse the name JSON string
                const currentLocale = '{{ app()->getLocale() }}'; // Get the current locale



                $('#subtasksTableBody').append(`
    <tr id="subtask-${response.subtask.id}">
        <td>${response.subtask.id}</td>
        <td>
            <input type="text" class="form-control subtask-name" value="${name[currentLocale]}" placeholder="{{ __('Enter name') }}">
        </td>
        <td>
            <select name="subtask_status" class="form-control">
                <option value="" disabled selected>{{ __('Select status') }}</option>
                <option value="To Do" ${response.subtask.status === 'To Do' ? 'selected' : ''}>{{ __('To Do') }}</option>
                <option value="In Progress" ${response.subtask.status === 'In Progress' ? 'selected' : ''}>{{ __('In Progress') }}</option>
                <option value="Done" ${response.subtask.status === 'Done' ? 'selected' : ''}>{{ __('Done') }}</option>
            </select>
        </td>
        <td>
            <button class="update-subtask-btn btn btn-success" data-id="${response.subtask.id}">{{ __('Update') }}</button>
        </td>
        <td>
            <button class="delete-subtask-btn btn btn-danger" data-id="${response.subtask.id}">{{ __('Delete') }}</button>
        </td>
        <td class="action-buttons">
            <a href="{{ url('comments/Subtask') }}/${response.subtask.id}" class="btn btn-info">{{ __('Comments') }}</a>
        </td>
    </tr>
`);

                $('#name_ar').val(''); // Clear the Arabic input field
                $('#name_en').val(''); // Clear the English input field
                $('#subtasksTableBody tr:contains("{{ __('No subtasks found') }}")').remove();
            },
            error: function(response) {
                alert('{{ __('Error adding subtask.') }}');
            }
        });
    });

 // Handle subtask update
 $(document).on('click', '.update-subtask-btn', function(e) {
        e.preventDefault();

        let subtaskId = $(this).data('id'); // Get subtask ID from the button's data-id
        let name_ar = $(`#subtask-${subtaskId} .subtask-name`).val(); // Arabic name
        let name_en = $(`#subtask-${subtaskId} .subtask-name`).val(); // English name
        let status = $(`#subtask-${subtaskId} select[name="subtask_status"]`).val();

        $.ajax({
            url: `{{ url('subtasks/update/') }}/${subtaskId}`,
            type: 'PUT',
            data: {
                name_ar: name_ar,
                name_en: name_en,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('{{ __('Subtask updated successfully.') }}');
                // Optionally update the row with the new data
                const name = JSON.parse(response.subtask.name);
                const currentLocale = '{{ app()->getLocale() }}';
                $(`#subtask-${subtaskId} .subtask-name`).val(name[currentLocale]); // Update name based on locale
                $(`#subtask-${subtaskId} select[name="subtask_status"]`).val(response.subtask.status); // Update status
            },
            error: function(response) {
                alert('{{ __('Error updating subtask.') }}');
            }
        });
    });


        // Handle subtask deletion
        $(document).on('click', '.delete-subtask-btn', function(e) {
            let subtaskId = $(this).data('id'); // Get subtask ID from the button's data-id

            if (confirm('{{ __('Are you sure you want to delete this subtask?') }}')) {
                $.ajax({
                    url: `{{ url('subtask/') }}/${subtaskId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $(`#subtask-${subtaskId}`).remove();
                        alert('{{ __('Subtask deleted successfully.') }}');
                    },
                    error: function(response) {
                        alert('{{ __('Error deleting subtask.') }}');
                    }
                });
            }
        });

    })
</script>
