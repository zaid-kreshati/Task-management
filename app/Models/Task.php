<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\ModelActivityObserver;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use App\Enums\TaskStatus;
use Spatie\Translatable\HasTranslations;




class Task extends Model
{
    use  HasFactory, SoftDeletes;

    protected $table = 'task';

    protected $fillable = ['task_description','status', 'end_flag', 'dead_line','updated_at'];

    protected $casts = [
        'dead_line' => 'datetime',
        'status' => TaskStatus::class, // Cast the status field to the TaskStatus enum

    ];







    public function cat()
{
    return $this->belongsToMany(Categories::class, 'cat_task', 'task_id', 'categories_id');
}


    public function user()
    {
        return $this->belongsToMany(User::class, 'user_task', 'task_id', 'user_id');
    }


    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    public function delete()
    {
        DB::transaction(function() {

            $this->subtasks()->delete();
            $this->comments()->delete();

            parent::delete();
        });
    }




    protected static function boot()
    {
        parent::boot();
        self::observe(ModelActivityObserver::class);  // Register the observer


    }


}
