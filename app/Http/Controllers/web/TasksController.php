<?php

namespace App\Http\Controllers\web;

use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;

use App\Services\SubtaskService;
use App\Services\categoryService;
use App\Http\Requests\Task\storeTaskRequest;
use App\Http\Requests\Task\updateTaskRequest;
use Illuminate\Support\Facades\Auth;
use Session;

class TasksController extends Controller
{

    protected $taskService;
    protected $subtaskService;
    protected $categoryService;


    public function __construct(TaskService $taskService, SubtaskService $subtaskService, CategoryService $categoryService)
    {
        $this->taskService = $taskService;
        $this->subtaskService = $subtaskService;
        $this->categoryService = $categoryService;

    }
    public function tlist($status = 'all')
    {
        $userId = Auth::id();
        $user = User::find($userId);

        // Retrieve categories and filter tasks by status
        $categories = $this->taskService->getTaskListWithPaginate($status);

        // Check if the request is an AJAX request
        if (request()->ajax()) {
            // Render the view to a string
            $html = view('partials.task_list', compact('categories', 'userId'))->render();

            // Return JSON response with rendered HTML and other data
            return response()->json([
                'html' => $html,
                'pagination' => (string) $categories->links('pagination::bootstrap-5'),
                'status' => $status,
                'message' => 'Tasks filtered successfully',
            ]);
        }

        // For non-AJAX requests, return the standard view
        return view('task_list', compact('categories', 'userId', 'status'));
    }





    public function index(Request $request)
    {
        // For leader
        if (auth()->user()->hasRole('leader')) {
            // Retrieve all tasks with pagination for the leader
            $tasks = $this->taskService->getAllTasksWithPaginate(); // Paginate with 5 tasks per page
        }
        // For regular users
        else {
            $userId = auth()->id();

            // Paginate tasks for the regular user
            $tasks = $this->taskService->getTaskByUserWithPaginate($userId);
        }

        // Retrieve users and categories
        $users = User::all();
        $Categories = $this->categoryService->getAllCategories();

        if ($request->ajax()) {
            return response()->json([
                'tasks' => view('partials.task_index', ['tasks' => $tasks])->render(),
                'pagination' => (string) $tasks->links('pagination::bootstrap-5')
            ]);
        }

        // Return to the view
        return view('task_index', compact('tasks', 'users', 'Categories'));
    }








    public function search(Request $request)
    {
        $query = $request->input('search');
        $tasks=$this->taskService->search($query);
        return response()->json($tasks);
    }


    public function store(storeTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->only('task_description_en','task_description_ar', 'dead_line'));


        $this->taskService->assignUsersToTask($task->id, $request->assign_users);
        $this->taskService->assignCategoriesToTask($task->id, $request->assign_categories);


        Session::put('task_id', $task->id);

        //return redirect()->route('create-checkout-session');

        return response()->json([
            'success' => true,
            'task_id' => $task->id,
        ]);

    }



    public function assignUsersView($id)
    {
        $task = $this->taskService->getTaskById($id);
        $users = User::all(['id', 'name']);
        return view('assign_users', compact('task', 'users'));
    }

    public function assignUsers(Request $request, $task_id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $this->taskService->assignUsersToTask($task_id, $request->user_ids);
        return redirect()->route('categories.assign', ['id' => $task_id])->with('success', 'Users assigned successfully!');
    }

    public function task_details($id)
    {
        $task = $this->taskService->getTaskById($id);
        $Subtasks = $this->taskService->getSubTasks($task->id);

        return view('task_details', compact('task', 'Subtasks'));
    }

    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return response()->json(['success' => true, 'message' => 'Task deleted successfully.'], 200);
    }

    public function restore($id)
    {
        $this->taskService->restoreTask($id);
        return redirect()->back();
    }



    public function update(updateTaskRequest $request, $id)
    {
        $task = $this->taskService->updateTask($id, $request->only( 'task_description_en', 'task_description_ar', 'dead_line', 'status'));

        //$Subtasks =$this->subtaskService->getSubTaskByTask($task->id);
        return response()->json(['success' => true,  'message' => 'Task updated successfully.']);
    }

    public function edit($id)
    {
        $task = $this->taskService->getTaskById($id);
        return view('editTask', compact('task'));
    }

    public function create()
    {
        return view('createTask.blade');
    }

    public function paymentSuccess(Task $task)
{
    $id = Session::get('task_id');

    // Activate the task after payment
    $this->taskService->restoreTask($id);

    return redirect()->route('tasks.index')->with('success', 'Task has been activated successfully after payment.');
}


}
