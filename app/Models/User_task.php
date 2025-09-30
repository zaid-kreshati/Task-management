<?php

namespace App\Models;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class User_task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_task';

    protected $fillable = [
        'user_id', 'task_id'];


    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}








