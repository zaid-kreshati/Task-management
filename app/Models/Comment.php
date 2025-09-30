<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\CommentInserted;
use App\Events\CommentDeleted;
use App\Observers\ModelActivityObserver;
use Spatie\Translatable\HasTranslations;



class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $dispatchesEvents = [
        'created' => CommentInserted::class,
        'deleted' => CommentDeleted::class,

    ];

    protected $fillable = [
        'comment',
        'commentable_type',
        'commentable_id',
        'user_id',
    ];

    // Define polymorphic relationship
    public function commentable()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();
        self::observe(ModelActivityObserver::class);  // Register the observer
    }



}

