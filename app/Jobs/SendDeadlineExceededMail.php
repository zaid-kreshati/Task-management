<?php
namespace App\Jobs;

use App\Mail\TaskDeadlineExceededMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDeadlineExceededMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;

    /**
     * Create a new job instance.
     *
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch all users assigned to the task
        $assignedUsers = $this->task->user;

        // Collect all email addresses
        $emails = $assignedUsers->pluck('email')->toArray();

        // Send a single email to all collected email addresses
        Mail::to($emails)->queue(new TaskDeadlineExceededMail($this->task));


    }
}
