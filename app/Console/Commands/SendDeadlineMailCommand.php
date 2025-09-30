<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SendMailService;

class SendDeadlineMailCommand extends Command
{
    protected $signature = 'mail:send-deadline';
    protected $description = 'Send deadline exceeded emails for tasks';
    protected $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        parent::__construct();
        $this->sendMailService = $sendMailService;
    }

    public function handle()
    {
        $this->sendMailService->handleDeadlineMails();
    }
}
