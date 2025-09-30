<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CheckUserComment
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }

        $commentId = $request->route('id');
        $comment = Comment::find($commentId);

        // Check if the comment exists
        if (!$comment) {
            return response()->json(['error' => 'Comment not found.'], 404);
        }

        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id === Auth::id()) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized. You cannot update or delete this comment.'], 403);
    }
}
