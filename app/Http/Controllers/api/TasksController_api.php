<?php

namespace App\Http\Controllers\api;

use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait; // Include the trait
use App\Http\Requests\Task\storeTaskRequest;
use App\Http\Requests\Task\updateTaskRequest;
use App\Exceptions\TaskNotFoundException;


class TasksController_api extends Controller_api
{
    use JsonResponseTrait; // Use the JsonResponseTrait

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function tlist( $status = 'all')
    {
        $categories = $this->taskService->getTaskListWithPaginate( $status);

        return $this->successResponse($categories, 'Tasks filtered successfully');
    }




    public function index()
    {
        // Check if the authenticated user is a leader
        if (auth()->user()->hasRole('leader')) {
            // Retrieve all tasks for leaders
            $tasks = $this->taskService->getAllTasksWithPaginate(); // Paginate with 5 tasks per page
        } else {
            // Retrieve tasks assigned to the specific user
            $tasks = $this->taskService->getTaskByUserWithPaginate();
        }

        return $this->successResponse($tasks); // Return the tasks in a consistent response format
    }





    public function search(Request $request)
    {
        $query = $request->input('task_description');
        $tasks = $this->taskService->search($query);

        return $this->successResponse($tasks);
    }


    public function store(storeTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->only('task_description_en', 'task_description_ar', 'dead_line'));
        $this->taskService->assignUsersToTask($task->id, $request->assign_users);
        $this->taskService->assignCategoriesToTask($task->id, $request->assign_categories);

        return $this->successResponse($task, 'Task created successfully!');


    }


    public function task_details($id)
    {
        $task = $this->taskService->getTaskById($id);
        $subtasks = $this->taskService->getSubTasks($task->id);

        return $this->successResponse($subtasks,$task);
    }

    public function destroy($id)
    {
        try{
                 $this->taskService->deleteTask($id);
                 $task = '';
                 if(!$task){
                    throw new TaskNotFoundException();
                 }
            return $this->successResponse(null, 'Task deleted successfully.');

            }catch (TaskNotFoundException $e) {
                return $e->render($id);
            }
    }



    public function restore($id)
{
        // Check if the task exists (including soft deleted)
        if (!$this->taskService->checkTaskExists($id)) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        // Check if the task exists and is not soft deleted
        if ($this->taskService->checkTaskExistsAndNotDeleted($id)) {
            return response()->json(['message' => 'Task already exists and is not soft deleted.'], 409);
        }

        // Proceed to restore the task
        $this->taskService->restoreTask($id);
        return $this->successResponse(null, 'Task restored successfully.');

}



    public function update(updateTaskRequest $request, $id)
    {

        try{
        $task = $this->taskService->updateTask($id, $request->only('task_description_en' , 'task_description_ar', 'dead_line', 'status'));

        return $this->successResponse($task, 'Task updated successfully.');
        }catch (TaskNotFoundException $e) {
            return $e->render($id);
        }
    }

}
