<?php

// app/Models/Menu.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_name',
        'menu_detail',
        'menu_type_id',
        'menu_price',
        'menu_status',
        'menu_image',
    ];

    public function menuType()
    {
        return $this->belongsTo(MenuTypes::class, 'menu_type_id');
    }
}
