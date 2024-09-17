<?php

// app/Models/MenuType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'menu_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'menu_type_name',
        'menu_type_detail',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
