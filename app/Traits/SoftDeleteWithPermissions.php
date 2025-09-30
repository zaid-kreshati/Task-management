<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Comment;

trait SoftDeleteWithPermissions
{
    /**
     * Check user role and permission, and perform soft delete with related data cleanup.
     *
     * @param int $taskId
     * @param string $role
     * @param string $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function softDeleteTaskWithRelated($taskId, $role, $permission)
    {
        // Check if the user has the specified role and permission
        if (!$this->checkRoleAndPermission($role, $permission)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        dd('yes');
        // Find the task
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        // Use relationships to retrieve subtasks and delete them with their comments
        foreach ($task->subtasks as $subtask) {
            // Check if the subtask exists before trying to delete comments
            if ($subtask) {
                foreach ($subtask->comments as $comment) {
                    // Check if the comment exists before trying to delete
                    if ($comment) {
                        $comment->delete();
                    }
                }
                $subtask->delete();
            }
        }

        // Soft delete associated comments for the task itself
        foreach ($task->comments as $comment) {
            if ($comment) {
                $comment->delete();
            }
        }

        // Soft delete the task
        $task->delete();

        return response()->json(['success' => 'Task and related data deleted successfully.'], 200);
    }

    /**
     * Check if the authenticated user has a specific role and permission.
     *
     * @param string $role
     * @param string $permission
     * @return bool
     */
    public function checkRoleAndPermission($role, $permission)
    {
        $user = Auth::user();
        return $user && $user->hasRole($role) && $user->can($permission);
    }
}
