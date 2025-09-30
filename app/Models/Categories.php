<?php
// Categories.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\ModelActivityObserver;
use Spatie\Translatable\HasTranslations;


class Categories extends Model
{
    use HasFactory, SoftDeletes,HasTranslations;

    protected $table = 'categories';

    protected $fillable = ['name', 'color'];

    //public $translatable = ['name']; // Make 'name' translatable
    public $translatable = ['name'];




    public function task():BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'cat_task', 'categories_id', 'task_id');
    }
     protected static function boot()
     {
         parent::boot();
         self::observe(ModelActivityObserver::class);  // Register the observer
     }


}



