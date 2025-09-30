<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OperationLog extends Model
{
    use HasFactory;

    protected $fillable = ['table_name', 'operation', 'data'];
}
