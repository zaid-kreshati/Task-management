<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getComments($type, $id)
    {
        $userId = Auth::id();
        $userIds = Auth::user()->hasRole('leader') ? User::pluck('id')->toArray() : [$userId];

        return $this->commentRepository->getComments($type, $id, $userIds);
    }

    public function CommentByUser()
    {
        return $this->commentRepository->CommentByUser();
    }

    public function CommentByLeader()
    {
        return $this->commentRepository->CommentByLeader();
    }


    public function createComment(array $data)
    {
        // Retrieve the authenticated user
        $user_id = Auth::id();
        $user = User::findOrfail($user_id);

        // Prepare data for the repository
        $commentData = [
            'comment' => json_encode([
                'en' => $data['comment_en'] ?? '',
                'ar' => $data['comment_ar'] ?? ''
            ]),
            'user_id' => $user->id,
            'commentable_type' => $data['commentable_type'],
            'commentable_id' => $data['commentable_id']
        ];

        // Delegate the creation of the comment to the repository
        return $this->commentRepository->createComment($commentData);
    }





    public function updateComment($id, array $data)
    {
        // Retrieve the existing comment
        $comment = $this->commentRepository->find($id);

        if (!$comment) {
            throw new \Exception('Comment not found.');
        }

        // Update the comment with the new data
        $commentData = [
            'comment' => json_encode([
                'en' => $data['comment_en'],
                'ar' => $data['comment_ar']
            ])
        ];

        return $this->commentRepository->update($comment, $commentData);
    }

    public function deleteComment($id)
    {
        $comment = $this->commentRepository->deleteComment($id);


        return $comment;
    }

    public function restoreComment($id)
    {
         $this->commentRepository->restoreComment($id);
    }
}
