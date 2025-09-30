<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\ModelActivityObserver;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\TaskStatus;
use Spatie\Translatable\HasTranslations;




class Subtask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'task_id', 'status'];

    protected $casts = [
        'status' => TaskStatus::class, // Cast the status field to the TaskStatus enum

    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Polymorphic Relationship
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($subtask) {
                // Restore comments related to the subtasks
                foreach ($subtask->comments as $comment) {
                $comment->delete();
            }
        });

        static::restoring(function ($subtask) {
            // Soft delete comments related to the subtask
            foreach ($subtask->comments as $comment) {
                $comment->delete();
            }
        });

        self::observe(ModelActivityObserver::class);  // Register the observer
    }


}
