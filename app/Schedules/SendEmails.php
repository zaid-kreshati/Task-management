<?php

namespace App\Schedules;

use App\Services\SendMailService;
use Illuminate\Console\Scheduling\Schedule;

class SendEmails
{
    protected $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        $this->sendMailService = $sendMailService;
    }

    /**
     * Define the scheduled task.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function __invoke(Schedule $schedule)
    {

        // Schedule the task to run every minute (adjust the frequency as needed)
        $schedule->call(function () {
            $this->sendMailService->handleDeadlineMails();
        });
    }
}
