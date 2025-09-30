<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Models\OperationLog;
use Illuminate\Database\Eloquent\Model;

class LogDatabaseOperations
{
    public function handle($event, $payload)
    {
        // Get the model instance from the payload
        $model = $payload[0];

        if ($model instanceof Model) {
            // Get the table name
            $tableName = $model->getTable();

            // Determine the type of operation
            $operation = '';
            if (str_contains($event, 'eloquent.created')) {
                $operation = 'insert';
            } elseif (str_contains($event, 'eloquent.deleted')) {
                $operation = 'delete';
            }

            // Prepare log data
            $logData = [
                'table_name' => $tableName,
                'operation' => $operation,
                'data' => json_encode($model->toArray()),
            ];

            // Store the log in the database
            OperationLog::create($logData);
        }
    }
}
