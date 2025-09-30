<?php

namespace App\Models;

use App\Models\Categories;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Cat_task extends Model
{
    use HasFactory;

    protected $table = 'cat_task';

    protected $fillable = [
        'categories_id', 'task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function categories() : BelongsTo
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}



