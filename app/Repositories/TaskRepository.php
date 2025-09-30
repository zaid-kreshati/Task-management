<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User_task;
use App\Models\Cat_task;
use App\Models\Subtask;
use App\Models\Categories;
use App\Exceptions\TaskNotFoundException;
use App\Enums\TaskStatus;



class TaskRepository
{
    public function getAllTasks()
    {
            return Task::all();
    }

    public function getAllTasksQuery()
    {
            return Task::query();
    }

    public function getAllTasksWithPaginate()
    {
            return Task::paginate(5);
    }

    public function getTasksList($status)
    {

            return Categories::with(['task' =>function ($query) use ($status){
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
            }])->get();
    }


    public function lastPage()  {
        // Get the total number of categories to determine the last page
        $totalTasks = Task::count();
        $tasksPerPage = 5; // Assuming you're paginating 10 categories per page
        $lastPage = ceil($totalTasks / $tasksPerPage);
        return $lastPage;
    }

    public function search($query)
    {
        $locale = app()->getLocale(); // Get the current application locale

        // Use JSON_UNQUOTE and JSON_EXTRACT to search within the 'name' JSON field for the current locale

        $user = auth()->user();

        $taskQuery = Task::query();

        // If the user is a leader, retrieve all tasks matching the query
        if ($user->hasRole('leader')) {
            $taskQuery->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(task_description, '$.$locale')) LIKE ?", ["%$query%"]);
        }
        // If the user is not a leader, retrieve tasks assigned to them
        else {
            $userId = $user->id;

            $taskQuery->whereHas('user', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(task_description, '$.$locale')) LIKE ?", ["%$query%"]);
        }

        return $taskQuery->get();
    }


    public function getTaskListWithPaginate($status = 'all')
    {
    return Categories::with(['task' => function ($query) use ($status) {
        if ($status !== 'all') {
            $query->where('status', $status);
        }
    }])->paginate(4);
    }

    public function getTaskByUser()
    {
        $userId = auth()->id();
        return Task::whereHas('user', function($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    public function getTaskByUserWithPaginate($userId)
    {

        return Task::whereHas('user', function($query) use ($userId) {
            $query->where('users.id', $userId);
        })->paginate(5);
    }



    public function getCategoriesWithUserTasks($userId, $status)
    {
        // Retrieve categories and tasks that are assigned to the specific user and filter by status
        return Categories::with(['task' => function ($query) use ($userId, $status) {
            $query->whereHas('user', function ($q) use ($userId) {
                $q->where('users.id', $userId); // Filter tasks assigned to the user
            })->where('status', $status); // Filter by status
        }])->get();
    }




        public function getTasksListAPI()
    {
            return Categories::with(['task'])->whereHas('user', function($query) use ($userId) {
                $query->where('users.id', $userId);
            });
    }


    public function getSubTasksByTaskId($taskId)
    {
        return Subtask::where('task_id', $taskId)->get();
    }

    public function getTaskById($id)
    {
        $task=Task::find($id);
        if (!$task) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function getTasksForUser($userId)
    {
        $userTasks = User_task::where('user_id', $userId)->pluck('task_id');
        return Task::whereIn('id', $userTasks)->get();
    }

    public function createTask(array $data)
    {

        $task= Task::create([
            'task_description' => json_encode([
                'en' => $data['task_description_en'],
                'ar' => $data['task_description_ar'],
            ]),
            'dead_line' => $data['dead_line'],
            'end_flag' => false,
            'status' => TaskStatus::TO_DO,
        ]);

        $task->delete();
        return $task;

        //return Task::create($data);
    }

    public function updateTask($id, array $data)
    {

        // Find the task
        $task = Task::find($id);

        if (!$task) {
            throw new TaskNotFoundException();
        }

        // Update the task with provided data
        $task->update([
            'task_description' => json_encode([
                'en' => $data['task_description_en'],
                'ar' => $data['task_description_ar'],
            ]),
            'dead_line' => $data['dead_line'],
            'status' => $data['status'],
        ]);

        // Check if the task should be marked as 'Done'
        $subtasks = $task->subtasks;

        if (!$subtasks->isEmpty()) {
            // Check if all subtasks are completed
            $allSubtasksCompleted = $subtasks->every(function ($subtask) {
                return $subtask->status === 'Done';
            });

            // Update the task status to 'Done' if all subtasks are completed
            if ($allSubtasksCompleted) {
                $task->update(['status' => 'Done']);
            }
        }

        return $task;
    }


    public function deleteTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            throw new TaskNotFoundException();
        }

        $task->user()->detach();
        $task->cat()->detach();
        $task->delete();
    }


     // Check if a task exists and is not soft deleted
     public function existsAndNotDeleted($id)
     {
         return Task::where('id', $id)->whereNull('deleted_at')->exists();
     }


      // Check if a task exists (including soft deleted)
    public function exists($id)
    {
        return Task::withTrashed()->where('id', $id)->exists();
    }

    public function restoreTask($id)
    {
        $task = Task::withTrashed()->find($id);
        $task->restore();
        return $task;
    }

    public function assignUsersToTask($taskId, array $userIds)
    {
        foreach ($userIds as $userId) {
            User_task::create([
                'task_id' => $taskId,
                'user_id' => $userId,
            ]);
        }
    }

    public function assignCategoriesToTask($taskId, array $categoryIds)
    {

        foreach ($categoryIds as $categoryId) {
            Cat_task::create([
                'task_id' => $taskId,
                'categories_id' => $categoryId,
            ]);
        }
    }

}
