@php
    use App\Enums\TaskStatus; // Declare the TaskStatus enum here
@endphp

@extends('layouts.BeeOrder_header')

@section('title', __('Task Details'))

@section('content')
    <div class="container">
        <h1>{{ __('Task Details') }}</h1>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @hasrole('leader')
            @if ($task)
                <form id="updateTaskForm">
                    @csrf
                    @method('PUT')
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Deadline') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Update') }}</th>
                                <th>{{ __('End Flag') }}</th>
                                <th>{{ __('Comments') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="task-{{ $task->id }}">
                                <td>{{ $task->id }}</td>
                                <td>
                                  
                                    @if ([app()->getLocale()])

                                    <input type="Text" id="task_description_en" value="{{ json_decode($task->task_description, true)['en'] }}">
                                    <input type="hidden" id="task_description_ar" value="{{ json_decode($task->task_description, true)['ar'] }}">

                                    @else
                                    <input type="hidden" id="task_description_en" value="{{ json_decode($task->task_description, true)['en'] }}">
                                    <input type="Text" id="task_description_ar" value="{{ json_decode($task->task_description, true)['ar'] }}">

                                    @endif
                                </td>
                                <td>
                                    <input type="datetime-local" id="dead_line" name="dead_line" class="edit-input" value="{{ $task->dead_line }}">
                                </td>
                                <td>
                                    <select id="task_status" name="status">
                                        <option value="{{ TaskStatus::TO_DO->value }}" {{ $task->status === TaskStatus::TO_DO->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::TO_DO->value) }}
                                        </option>
                                        <option value="{{ TaskStatus::IN_PROGRESS->value }}" {{ $task->status === TaskStatus::IN_PROGRESS->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::IN_PROGRESS->value) }}
                                        </option>
                                        <option value="{{ TaskStatus::DONE->value }}" {{ $task->status === TaskStatus::DONE->value ? 'selected' : '' }}>
                                            {{ __(TaskStatus::DONE->value) }}
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="update-task-btn btn">{{ __('Update') }}</button>
                                </td>
                                <td>
                                    @if ($task->end_flag)
                                        <img src="{{ asset('BeeOrder/img/true.png') }}" alt="{{ __('True') }}" class="icon">
                                    @else
                                        <img src="{{ asset('BeeOrder/img/false.png') }}" alt="{{ __('False') }}" class="icon">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('comments.index', ['type' => 'Task', 'id' => $task->id]) }}" class="btn btn-info">{{ __('Comments') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            @else
                <p>{{ __('No task details available.') }}</p>
            @endif
        @else
            @if ($task)
                <!-- Code for non-leaders goes here -->
            @else
                <p>{{ __('No task details available.') }}</p>
            @endif
        @endrole

        @include('partials.subtask')

        <!-- AJAX Script to handle adding, updating, and deleting subtasks -->
        <script>
            $(document).ready(function() {
                // Handle task update
                $('#updateTaskForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    let taskId = "{{ $task->id }}"; // Get the task ID from the blade variable
                    let task_description_en = $(`#task_description_en`).val();
                    let task_description_ar = $(`#task_description_ar`).val();

                    let dead_line = $('#dead_line').val();
                    let status = $('#task_status').val();

                    $.ajax({
                        url: `/task/update/${taskId}`, // Adjust the URL to match your routes
                        type: 'PUT',
                        data: {
                            task_description_ar: task_description_ar,
                            task_description_en: task_description_en,
                            dead_line: dead_line,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Task updated successfully.');
                        },
                        error: function(response) {
                            alert('Error updating task: ' + response.responseJSON.message);
                        }
                    });
                });
            });
        </script>
    </div>
@endsection
