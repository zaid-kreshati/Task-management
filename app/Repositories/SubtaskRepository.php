<?php

namespace App\Repositories;

use App\Models\Subtask;
use App\Models\Comment;
use App\Enums\TaskStatus;

class SubtaskRepository
{
    /**
     * Create a new subtask.
     *
     * @param array $data
     * @return Subtask
     */
    public function create(array $data): Subtask
    {

        return Subtask::create([
            'name' => json_encode([
                'en' => $data['name_en'],
                'ar' => $data['name_ar'],
            ]),
            'task_id' => $data['task_id'],
            'status' => TaskStatus::TO_DO,
        ]);


    }

    public function getSubTaskByTask( $taskId)
    {
        return Subtask::where('task_id',$taskId)->get();
    }

    /**
     * Update an existing subtask.
     *
     * @param Subtask $subtask
     * @param array $data
     * @return Subtask
     */
    public function update($id, $data)
    {
        // Find the subtask by ID
        $subtask = Subtask::findOrFail($id);

        // Update the subtask with the provided data
        $subtask->update($data);

        return $subtask;
    }


    /**
     * Delete a subtask and its associated comments.
     *
     * @param Subtask $subtask
     * @return bool|null
     */
    public function delete( $id): ?bool
    {

        $subtask = Subtask::findOrFail($id);

         $subtask->delete();
         return true;

    }

    /**
     * Restore a deleted subtask.
     *
     * @param int $id
     * @return Subtask|null
     */
    public function restore(int $id)
    {
        $subtask = Subtask::withTrashed()->find($id);
        if ($subtask) {
            $subtask->restore();
        }
    }
}
