<?php

namespace App\Http\Controllers\api;

use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\Comment\updateCommentRequest;
use App\Http\Requests\Comment\storeCommentRequest;


class CommentController_api extends Controller_api
{
    use JsonResponseTrait; // Use the trait for JSON responses

    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($type, $id)
    {
        $Comments = $this->commentService->getComments($type, $id);
        $userId = Auth::id();

        return $this->successResponse(compact('Comments', 'id', 'type', 'userId'), 'Comments fetched successfully.');
    }

    public function CommentByUser()
    {
        $Comments = $this->commentService->CommentByUser();
        $userId = Auth::id();

        return $this->successResponse(compact('Comments', 'userId'), 'User Comments fetched successfully.');
    }

    public function CommentByLeader()
    {
        $Comments = $this->commentService->CommentByLeader();
        $userId = Auth::id();

        return $this->successResponse(compact('Comments', 'userId'), 'Comments fetched successfully.');
    }



    public function store(storeCommentRequest $request)
    {

        $comment = $this->commentService->createComment($request->only('comment_en', 'comment_ar' ,'commentable_type', 'commentable_id'));

        return response()->json([
            'id' => $comment->id,
            'comment' => json_decode($comment->comment, true), // Decode to get both 'en' and 'ar'
        ]);
        //return $this->successResponse($comment, 'Comment created successfully.');
    }

    public function update(updateCommentRequest $request, $id)
    {


        $comment = $this->commentService->updateComment($id, $request->only('comment_en', 'comment_ar'));

        return $this->successResponse($comment, 'Comment updated successfully.');
    }

    public function destroy($id)
    {
        $this->commentService->deleteComment($id);

        return $this->successResponse(null, 'Comment deleted successfully.');
    }

    public function restore($id)
    {
        $this->commentService->restoreComment($id);
        return $this->successResponse(null, 'Comment restored successfully.');
    }
}
