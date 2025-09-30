<?php
namespace App\Services;

use App\Repositories\SendMailRepository;



class SendMailService
{

    protected $mailRepository;

    public function __construct(SendMailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    public function handleDeadlineMails()
    {
        $this->mailRepository->sendMails();
    }

}
