<?php

// app/Models/MenuType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTypes extends Model
{
    use HasFactory;

    protected $table = "menu_types";
    
    protected $fillable = [
        'menu_type_name',
        'menu_type_detail',
    ];
}
