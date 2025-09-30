<?php

namespace App\Observers;

use App\Models\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ModelActivityObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Model $model)
    {
        $this->logActivity('insert', $model);
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted(Model $model)
    {
        $this->logActivity('delete', $model);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Model $model)
    {
        $this->logActivity('update', $model);
    }

    /**
     * Log model activity.
     *
     * @param  string  $action
     * @param  Model   $model
     */
    private function logActivity(string $action, Model $model)
    {
        // Get the authenticated user's name, or 'Unknown' if not authenticated
        $userName = Auth::check() ? Auth::user()->name : 'unknown';

        // Initialize variables
        $newModel = $oldModel = $deletedModel = null;
        $createdTime = $updatedTime = $deletedTime = null;

        // Get model attributes without timestamps
        $modelAttributes = $this->getModelAttributesWithoutTimestamps($model);

        if ($action === 'insert') {
            $newModel = json_encode($modelAttributes);
            $createdTime = now();
        } elseif ($action === 'delete') {
            $deletedModel = json_encode($modelAttributes);
            $deletedTime = now();
        } elseif ($action === 'update') {
            // Get the changed data (new values) without timestamps
            $changedData = $this->removeTimestamps($model->getChanges());
            // Get the original data (old values) without timestamps
            $originalData = $this->removeTimestamps($model->getOriginal());

            // Prepare data for logging
            $oldValues = [];
            $newValues = [];

            // Only include attributes that have actual changes
            foreach ($changedData as $attribute => $newValue) {
                // Compare the old value with the new value strictly
                if ($originalData[$attribute] !== $newValue) {
                    $oldValues[$attribute] = $originalData[$attribute] ?? null; // Old value
                    $newValues[$attribute] = $newValue; // New value
                }
            }

            if (!empty($newValues)) {
                $newModel = json_encode($newValues);
                $oldModel = json_encode($oldValues);
                $updatedTime = now();
            }
        }

        // Only log if there's something to log
        if ($newModel || $deletedModel || $oldModel) {
            Logs::create([
                'model' => get_class($model),
                'model_id' => $model->id,
                'action' => $action,
                'new_model' => $newModel,
                'old_model' => $oldModel,
                'deleted_model' => $deletedModel,
                'action_by' => $userName,
                'createdTime' => $createdTime,
                'updatedTime' => $updatedTime,
                'deletedTime' => $deletedTime,
            ]);
        }
    }

    /**
     * Get model attributes without timestamps.
     *
     * @param  Model  $model
     * @return array
     */
    private function getModelAttributesWithoutTimestamps(Model $model)
    {
        $attributes = $model->getAttributes();

        // Remove `created_at` and `updated_at` if the model uses timestamps
        if ($model->usesTimestamps()) {
            unset($attributes[$model->getCreatedAtColumn()]);
            unset($attributes[$model->getUpdatedAtColumn()]);
        }

        // Remove `deleted_at` if the model uses soft deletes
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            unset($attributes['deleted_at']);
        }

        return $attributes;
    }

    /**
     * Remove timestamp fields from given attributes.
     *
     * @param  array  $attributes
     * @return array
     */
    private function removeTimestamps(array $attributes)
    {
        unset($attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);
        return $attributes;
    }
}
