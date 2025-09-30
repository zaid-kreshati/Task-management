<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ModelActivityObserver;
use Spatie\Translatable\HasTranslations;

class Mail extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        self::observe(ModelActivityObserver::class);  // Register the observer
    }


}
