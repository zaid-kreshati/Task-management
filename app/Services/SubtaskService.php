<?php

namespace App\Services;
use App\Models\Subtask;

use App\Repositories\SubtaskRepository;

class SubtaskService
{
    protected $subtaskRepository;

    public function __construct(SubtaskRepository $subtaskRepository)
    {
        $this->subtaskRepository = $subtaskRepository;
    }

    /**
     * Create a new subtask.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): Subtask
    {
         return $this->subtaskRepository->create($data);

    }

    /**
     * Update an existing subtask.
     *
     * @param Subtask $subtask
     * @param array $data
     * @return mixed
     */

     public function update( $id, array $data)
     {
         // Prepare the data for the repository
         $subtaskData = [
             'name' => json_encode([
                 'ar' => $data['name_ar'],
                 'en' => $data['name_en'],
             ]),
             'status' => $data['status'],
         ];

         // Pass the data to the repository for updating the subtask
         return $this->subtaskRepository->update($id, $subtaskData);
     }

    public function getSubTaskByTask( $taskId)
    {
        return $this->subtaskRepository->getSubTaskByTask($taskId);

    }

    /**
     * Delete a subtask.
     *
     * @param Subtask $subtask
     * @return bool|null
     */
    public function delete( $id)
    {
         return $this->subtaskRepository->delete($id);
    }

    /**
     * Restore a deleted subtask.
     *
     * @param int $id
     * @return Subtask|null
     */
    public function restore(int $id)
    {
        $subtask = $this->subtaskRepository->restore($id);
        return $subtask;
    }

}
