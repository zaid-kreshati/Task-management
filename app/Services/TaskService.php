<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\CategoryRepository;

use Illuminate\Support\Facades\Auth;


class TaskService
{
    protected $taskRepository;
    protected $categoryRepository;


    public function __construct(TaskRepository $taskRepository, CategoryRepository $categoryRepository )
    {
        $this->taskRepository = $taskRepository;
        $this->categoryRepository = $categoryRepository;

    }

    public function getAllTasks( )
    {
        return $this->taskRepository->getAllTasks();
    }

    public function getAllTasksQuery( )
    {
        return $this->taskRepository->getAllTasksQuery();
    }


    public function getAllTasksWithPaginate( )
    {
        return $this->taskRepository->getAllTasksWithPaginate();
    }

    public function getTaskByUserWithPaginate($userId )
    {
        return $this->taskRepository->getTaskByUserWithPaginate($userId);
    }

    public function getTaskByUser()
    {
        return $this->taskRepository->getTaskByUser();
    }


    public function getTaskList()
    {

        return $this->taskRepository->getTasksList();


    }

    public function getTaskListWithPaginate($status)
    {

        return $this->taskRepository->getTaskListWithPaginate($status);


    }

    public function search($query)
    {
        return $this->taskRepository->search($query);
    }

    public function getCategoriesWithTasks($status)
    {

        $user = Auth::user();
        // Check if the user is a leader
        $isLeader = $user->hasRole('leader');

        if ($isLeader) {
            // Get all categories and all tasks for leaders
            $categories = $this->taskRepository->getTasksList($status);
        } else {
            // Get categories with tasks assigned to the specific user
            $categories = $this->taskRepository->getCategoriesWithUserTasks($user->id,$status);
        }




        return $categories;
    }



      // Check if the task exists (including soft deleted)
      public function checkTaskExists($id)
      {
          return $this->taskRepository->exists($id);
      }

     // Check if the task exists and is not soft deleted
     public function checkTaskExistsAndNotDeleted($id)
     {
         return $this->taskRepository->existsAndNotDeleted($id);
     }

    public function getTaskById($userId)
    {
        return $this->taskRepository->getTaskById($userId);

    }

    public function getSubTasks($taskId)
    {
        return $this->taskRepository->getsubTasksByTaskId($taskId);

    }
    public function getTasksForUserOrLeader()
    {
        $userId = Auth::id();

        if (Auth::user()->hasRole('leader')) {
                return $this->taskRepository->getAllTasks();
        } else {
                return $this->taskRepository->getTasksForUser($userId);
        }
    }

    public function createTask(array $data)
    {
        $task = $this->taskRepository->createTask($data);
        return $task;
    }

    public function lastpage( )
    {
        return $this->taskRepository->lastpage();

    }



    public function updateTask($id, array $data)
    {
        $task = $this->taskRepository->updateTask($id, $data);
        return $task;
    }

    public function deleteTask($id)
    {
        $this->taskRepository->deleteTask($id);
    }

    public function restoreTask($id)
    {
        $this->taskRepository->restoreTask($id);
    }

    public function assignUsersToTask($taskId, array $userIds)
    {
        $this->taskRepository->assignUsersToTask($taskId, $userIds);
    }

    public function assignCategoriesToTask($taskId, array $categoryIds)
    {
        $this->taskRepository->assignCategoriesToTask($taskId, $categoryIds);
    }

}
