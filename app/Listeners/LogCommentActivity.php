<?php
// app/Listeners/LogCommentActivity.php

namespace App\Listeners;

use App\Events\CommentInserted;
use App\Events\CommentDeleted;
use App\Models\CommentLog;
use App\Models\User; 


class LogCommentActivity
{
    /**
     * Handle the event when a comment is inserted.
     *
     * @param  \App\Events\CommentInserted  $event
     * @return void
     */
    public function handleCommentInserted(CommentInserted $event)
    {
        $user=User::findOrFail($event->comment->user_id);
        CommentLog::create([
            'action' => 'insert',
            'comment_id' => $event->comment->id,
            'type' => $event->comment->commentable_type,
            'user_id' => $event->comment->user_id,
            'user_name' => $user->name,
            'comment' => $event->comment->comment,


        ]);
    }

    /**
     * Handle the event when a comment is deleted.
     *
     * @param  \App\Events\CommentDeleted  $event
     * @return void
     */
    public function handleCommentDeleted(CommentDeleted $event)
    {
        $user=User::findOrFail($event->comment->user_id);

        CommentLog::create([
            'action' => 'delete',
            'comment_id' => $event->comment->id,
            'type' => $event->comment->commentable_type,
            'user_id' => $event->comment->user_id,
            'user_name' => $user->name,
            'comment' => $event->comment->comment,
            
        ]);
    }

  

    /**
     * Handle both events.
     */
    public function handle($event)
    {
        // if ($event instanceof CommentInserted) {
        //     $this->handleCommentInserted($event);
        // } elseif ($event instanceof CommentDeleted) {
        //     $this->handleCommentDeleted($event);
        // }
    }
}
