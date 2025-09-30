<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use Carbon\Carbon;

class TaskDeadlineExceededMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $taskDescription = $this->task->task_description;
        // Format the deadline to include both date and time
        $deadline = Carbon::parse($this->task->dead_line)->format('l, F j, Y \a\t g:i A');

        return $this->view('task_deadline_exceeded')
                    ->with([
                        'taskDescription' => $taskDescription,
                        'deadline' => $deadline,
                    ]);
    }
}
