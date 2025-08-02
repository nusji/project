<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRating extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = ['menu_id', 'rating', 'comment'];
}
