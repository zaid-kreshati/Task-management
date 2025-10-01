<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Deadline Exceeded</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            width: 150px;
        }
        .content {
            margin: 20px 0;
        }
        .header .logo {
            display: flex;
            align-items: center;
        }
        .header .logo img {
            height: 50px; /* Adjust as needed */
            border-radius: 50%; /* Circular logo */
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path().'/img/taskmanager.png') }}">

        </div>

        <div class="content">
            <p>Dear Team,</p>

            <p>This is a notification regarding the task titled "<strong>{{ $task->task_description }}</strong>".</p>

            <p>We would like to inform you that the deadline for this task has been exceeded. Please review the task and take any necessary actions to address the overdue status.</p>

            <p>Task Details:</p>
            <ul>
                <li><strong>Task :</strong> {{ $task->task_description }}</li>
                <li><strong>Deadline:</strong> {{ $task->dead_line->format('F j, Y, g:i a') }}</li>
                {{-- <li><strong>Status:</strong> {{ $task->status }}</li> --}}
            </ul>

            <p>If you have any questions or need further assistance, please do not hesitate to contact us.</p>

            <p>Thank you for your attention to this matter.</p>

            <p>Best regards,</p>
            <p>The task management Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }}  All rights reserved.</p>
            <p>Damascus, Syria</p>
            <p>Email: support@task management.com | Phone: +963</p>
        </div>
    </div>
</body>
</html>
