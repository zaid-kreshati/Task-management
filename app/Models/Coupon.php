<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory; // Add HasFactory trait

    protected $fillable = ['code', 'discount', 'is_active'];
  
}
