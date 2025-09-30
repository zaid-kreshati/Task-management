<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class CommentRepository
{

    public function getComments($type, $id, $userIds)
    {
            return Comment::where('commentable_type', $type)
                ->where('commentable_id', $id)
                ->whereIn('user_id', $userIds)
                ->get();
    }

    public function CommentByUser()
    {
        $user_id=Auth::id();
            return Comment::where('user_id', $user_id)
                ->get();
    }

    public function CommentByLeader()
    {
            return Comment::all();
    }



    public function createComment(array $data)
    {
        // Create the comment in the database and return the created comment
        return Comment::create($data);
    }



    public function find($id)
    {
        return Comment::find($id);
    }

    public function update(Comment $comment, array $data)
    {
        $comment->update($data);
        return $comment;
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }

    public function restoreComment($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->restore();
    }


}
