<?php
namespace App\Enums;


enum TaskStatus: string
{
    case TO_DO = 'To Do';
    case IN_PROGRESS = 'In Progress';
    case DONE = 'Done';

    public static function getLocalizedValues(): array
    {
        return [
            'To Do' => __('To Do'),
            'In Progress' => __('In Progress'),
            'Done' => __('Done'),
        ];
    }
}
