<?php

namespace App\Repositories;

use App\Models\Task;
use Carbon\Carbon;
use App\Jobs\SendDeadlineExceededMail;


class SendMailRepository
{


    public function sendMails()
    {
        $delayInSeconds = 0;

        // Fetch tasks whose deadlines are exceeded and are not marked as ended
        $tasks = Task::where('dead_line', '<', Carbon::now())
                    ->where('end_flag', '0') 
                    ->withoutTrashed()
                    ->get();

        // Start with a base delay (in seconds)

        // Dispatch emails for each task with an exceeded deadline
        foreach ($tasks as $task) {
            $task->update(['end_flag' => '1']);

            // Dispatch email with increasing delay for each task
            SendDeadlineExceededMail::dispatch($task)->delay(now()->addSeconds($delayInSeconds));

            $delayInSeconds += 5;

        }
    }

}

