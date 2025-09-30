<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CommentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'type',
        'comment_id',
        'user_id',
        'user_name',
        'comment',

    ];
}

