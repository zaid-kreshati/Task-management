<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Categories;
use App\Models\Task;
use App\Models\User_Task;
use App\Models\Cat_Task;
use App\Models\Subtask;
use App\Models\Comment;
use App\Enums\TaskStatus;

class TaskSeeder extends Seeder
{
    /**
     * Seed the tasks table.
     *
     * @return void
     */
    public function run()
    {
        // Generate dummy data for tasks
        $tasks = [
            [
                'task_description' => json_encode(['en' => 'task1', 'ar' => 'المهمة1']),
                'end_flag' => false,
                'dead_line' => now()->addDays(7),
                'status' => TaskStatus::TO_DO,


            ],
            [
                'task_description' => json_encode(['en' => 'task2', 'ar' => 'المهمة2']),
                'end_flag' => false,
                'dead_line' => now()->addDays(5),
                'status' => TaskStatus::IN_PROGRESS,


            ]
            ,
            [
                'task_description' => json_encode(['en' => 'task3', 'ar' => 'المهمة3']),

                'end_flag' => false,
                'dead_line' => now()->addDays(3),
                'status' => TaskStatus::TO_DO,


            ],
            [
                'task_description' => json_encode(['en' => 'task4', 'ar' => 'المهمة4']),

                'status' => TaskStatus::IN_PROGRESS,

                'end_flag' => true,
                'dead_line' => now()->addDays(2),

            ],
            [
                'task_description' => json_encode(['en' => 'task5', 'ar' => 'المهمة5']),

                'status' => TaskStatus::IN_PROGRESS,

                'end_flag' => true,
                'dead_line' => now()->addDays(2),

            ],
            [
                'task_description' => json_encode(['en' => 'task6', 'ar' => 'المهمة6']),

                'end_flag' => true,
                'dead_line' => now()->addDays(2),
                'status' => TaskStatus::TO_DO,


            ],
            [
                'task_description' => json_encode(['en' => 'task7', 'ar' => 'المهمة7']),
                'status' => TaskStatus::DONE,

                'end_flag' => true,
                'dead_line' => now()->addDays(2),

            ],
            [
                'task_description' => json_encode(['en' => 'task8', 'ar' => 'المهمة8']),
                'status' => TaskStatus::TO_DO,

                'end_flag' => true,
                'dead_line' => now()->addDays(2),

            ],
            [
                'task_description' => json_encode(['en' => 'task9', 'ar' => 'المهمة9']),
                'status' => TaskStatus::DONE,

                'end_flag' => true,
                'dead_line' => now()->addDays(2),

            ],
        ];

        foreach ($tasks as $taskData) {
            // Create task
            $task = Task::create($taskData);

            // Get all users and categories
            $users = User::all();
            $categories = Categories::all();

            // Randomly select users and categories
            $randomUsers = $users->random(rand(2, $users->count()));
            $randomCategories = $categories->random(rand(1, $categories->count()));

            // Attach users to the task
            foreach ($randomUsers as $user) {
                User_Task::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                ]);
            }

            // Attach categories to the task
            foreach ($randomCategories as $category) {
                Cat_Task::create([
                    'task_id' => $task->id,
                    'categories_id' => $category->id,
                ]);
            }

            // Create subtasks for the task
            $subtaskCounter = 1;
            $numberOfSubtasks = rand(2, 5); // Random number of subtasks

            for ($i = 0; $i < $numberOfSubtasks; $i++) {
                $subtaskName = json_encode([
                    'en' => 'Subtask ' . $subtaskCounter,
                    'ar' => 'المهمة الفرعية ' . $subtaskCounter,
                ]);
                $subtaskCounter++;

                $subtask = Subtask::create([
                    'name' => $subtaskName,
                    'task_id' => $task->id,
                ]);

                // Create comments for each subtask
                $randomUser = $randomUsers->random();
                $commetSubTaskName=json_encode([
                    'en' => 'comment1',
                    'ar' => 'التعليق الاول',
                ]);
                $commetSubTask=Comment::create([
                    'commentable_type' => 'Subtask',
                    'commentable_id' => $subtask->id,
                    'comment' => $commetSubTaskName,
                    'user_id' => $randomUser->id,
                ]);
            }

            // Create comments for the task
            $randomUser = $randomUsers->random();

            $commetTaskName=json_encode([
                'en' => 'comment1',
                'ar' => 'التعليق الاول',
            ]);
            $commetTask=Comment::create([
                'commentable_type' => 'Task',
                'commentable_id' => $task->id,
                'comment' => $commetTaskName,
                'user_id' => $randomUser->id,
            ]);

        }
    }
}
