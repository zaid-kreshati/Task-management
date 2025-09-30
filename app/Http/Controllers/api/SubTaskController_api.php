<?php

namespace App\Http\Controllers\api;

use App\Services\SubtaskService;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\Subtask;
use App\Http\Requests\SubTask\updateSubTaskRequest;
use App\Http\Requests\SubTask\storeSubTaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SubTaskController_api extends Controller_api
{
    use JsonResponseTrait; // Use the JsonResponseTrait for consistent JSON responses

    protected $subtaskService;

    public function __construct(SubtaskService $subtaskService)
    {
        $this->subtaskService = $subtaskService;
    }

    public function storeSubtask(storeSubTaskRequest $request)
    {
        // Create the subtask
        $subtask = $this->subtaskService->create($request->only(['task_id', 'name_en', 'name_ar']));

        // Use the trait to return a JSON response
        return $this->successResponse($subtask, 'Subtask added successfully.');
    }

    public function update_subtask(updateSubTaskRequest $request,  $id)
    {
        $updatedSubtask = $this->subtaskService->update($id, $request->only('name_en', 'name_ar', 'status'));

        // Use the trait to return a JSON response
        return $this->successResponse($updatedSubtask, 'Subtask updated successfully.');

    }

    public function destroy_subtask( $id)
    {
        try{

        // Delete the subtask
        $this->subtaskService->delete($id);

        // Use the trait to return a JSON response
        return $this->successResponse(null, 'Subtask deleted successfully.');

    }catch(ModelNotFoundException $e) {
        return response()->json(['message' => 'subTask not found.'], 404);

    }
}




    public function restore($id)
    {
        $this->subtaskService->restore($id);
        // Use the trait to return a success message after restoration
        return $this->successResponse(null, 'Subtask restored successfully.');

    }
}
