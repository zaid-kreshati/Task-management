<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Comment\updateCommentRequest;
use App\Http\Requests\Comment\storeCommentRequest;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($type, $id)
    {
        $Comments = $this->commentService->getComments($type, $id);
        $userId = Auth::id();

        return view('CommentIndex', compact('Comments', 'id', 'type', 'userId'));
    }

    public function store(storeCommentRequest $request)
    {

        $comment = $this->commentService->createComment($request->only('comment_en', 'comment_ar' ,'commentable_type', 'commentable_id'));

        return response()->json($comment);
    }

    public function update(updateCommentRequest $request, $id)
    {
    ;

        // Call the service to update the comment
        $comment = $this->commentService->updateComment($id, $request->only(['comment_en', 'comment_ar']));

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $this->commentService->deleteComment($id);

        return response()->json(['success' => 'Comment deleted successfully.']);
    }

    public function restore($id)
    {
        $this->commentService->restoreComment($id);
        return redirect()->back();
    }


}
