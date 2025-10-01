@extends('layouts.header')

@section('title', __('Comments'))

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>{{ __('Comment View') }}</h1>
        <!-- Add Comment Form -->
        <form id="addCommentForm">
            @csrf
            <input type="hidden" name="commentable_type" id="commentable_type" value="{{ $type }}">
            <input type="hidden" name="commentable_id" id="commentable_id" value="{{ $id }}">
            <input type="hidden" name="user_id" id="user_id" value="{{ $userId }}">

            <div>
                <label for="comment_en">{{ __('Enter your comment(English)') }}</label>
                <input type="text" name="comment_en" id="comment_en" class="form-control" required >

                <label for="comment_ar">{{ __('Enter your comment(Arabic)') }}</label>
                <input type="text" name="comment_ar" id="comment_ar" class="form-control" required >

            </div>

            <button type="submit" class="btn btn-primary mt-2">{{ __('Submit') }}</button>
        </form>

        <!-- Comment Table -->
        <table id="tasksTable" class="table mt-3">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Comment') }}</th>
                    <th>{{ __('Update') }}</th>
                    <th>{{ __('Delete') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($Comments as $comment)
                    <tr id="comment-{{ $comment->id }}">
                        <td>{{ $comment->id }}</td>



                        <td>
                            @if (app()->getLocale() == 'en')
                                <input type="text" id="comment_en_{{ $comment->id }}" value="{{ json_decode($comment->comment, true)['en'] }}" class="form-control">
                                <input type="hidden" id="comment_ar_{{ $comment->id }}" value="{{ json_decode($comment->comment, true)['ar'] }}">
                            @else
                                <input type="hidden" id="comment_en_{{ $comment->id }}" value="{{ json_decode($comment->comment, true)['en'] }}">
                                <input type="text" id="comment_ar_{{ $comment->id }}" value="{{ json_decode($comment->comment, true)['ar'] }}" class="form-control">
                            @endif
                        </td>

                        <td>
                            <button class="update-comment-btn btn btn-success" data-id="{{ $comment->id }}">{{ __('Update') }}</button>
                        </td>
                        <td>
                            <button class="delete-comment-btn btn btn-danger" data-id="{{ $comment->id }}">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('No comments found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- AJAX Script to handle adding, updating, and deleting comments -->
    <script>
        $(document).ready(function() {
            // Handle form submission to add a new comment
            $('#addCommentForm').submit(function(e) {
                e.preventDefault(); // Prevent form from submitting normally

                // Collect form data
                let formData = {
                    commentable_type: $('#commentable_type').val(),
                    commentable_id: $('#commentable_id').val(),
                    comment_en: $('#comment_en').val(),
                    comment_ar: $('#comment_ar').val(),

                    user_id: $('#user_id').val(),
                    _token: '{{ csrf_token() }}'
                };

                // AJAX request to add comment
                $.ajax({
                    url: '{{ route('comments.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        const comment = JSON.parse(response.comment); // Decode the task description
                                const currentLocale = '{{ app()->getLocale() }}';
                                const localizedComment = comment[currentLocale] || comment['en'];



                        $('#tasksTable tbody').append(`
                            <tr id="comment-${response.id}">
                                <td>${response.id}</td>
                                <td>
                                    <input type="text" value="${localizedComment}" class="form-control" data-id="${response.id}">
                                </td>
                                <td>
                                    <button class="update-comment-btn btn btn-success" data-id="${response.id}">{{ __('Update') }}</button>
                                </td>
                                <td>
                                    <button class="delete-comment-btn btn btn-danger" data-id="${response.id}">{{ __('Delete') }}</button>
                                </td>
                            </tr>
                        `);
                        $('#comment_en').val('');
                        $('#comment_ar').val('');

                        $('#tasksTable tr:contains("{{ __('No comments found.') }}")').remove();
                    },
                    error: function(xhr) {
                        alert('{{ __('An error occurred while adding the comment.') }}');
                    }
                });
            });

            // Handle updating a comment
// Handle updating a comment
$(document).on('click', '.update-comment-btn', function() {
    let commentId = $(this).data('id');

    // Get the values for English and Arabic comments using dynamic IDs
    let comment_en = $(`#comment_en_${commentId}`).val(); // Corrected selector for English comment
    let comment_ar = $(`#comment_ar_${commentId}`).val(); // Corrected selector for Arabic comment

    console.log(comment_en); // For debugging purposes
    console.log(comment_ar); // For debugging purposes

    $.ajax({
        url: `{{ url('comments') }}/${commentId}`,
        type: 'PUT',
        data: {
            comment_en: comment_en,
            comment_ar: comment_ar,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            alert('{{ __('Comment updated successfully.') }}');
            // Optionally update the UI to reflect the changes if necessary
        },
        error: function(response) {
            console.log(response.responseJSON); // Log the full error response for debugging
            alert('{{ __('An error occurred while updating the comment.') }}');
        }
    });
});



            // Handle deleting a comment
            $(document).on('click', '.delete-comment-btn', function() {
                let commentId = $(this).data('id');

                if (confirm('{{ __('Are you sure you want to delete this comment?') }}')) {
                    $.ajax({
                        url: `{{ url('comments') }}/${commentId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $(`#comment-${commentId}`).remove();
                            alert('{{ __('Comment deleted successfully.') }}');
                        },
                        error: function(xhr) {
                            alert('{{ __('An error occurred while deleting the comment.') }}');
                        }
                    });
                }
            });
        });
    </script>
@endsection

</body>
</html>
