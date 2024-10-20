<?php

// app/Models/Menu.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'menu_name',
        'menu_detail',
        'menu_type_id',
        'menu_price',
        'menu_status',
        'menu_taste',
        'menu_image',
    ];

    public function menuType()
    {
        return $this->belongsTo(MenuType::class);
    }
    public function recipes()
    {
        return $this->hasMany(MenuRecipe::class);
    }

    public function ingredients()
    {
        // ใช้ 'menu_recipes' เป็นชื่อ Pivot Table
        return $this->belongsToMany(Ingredient::class, 'menu_recipes')
                    ->withPivot('amount');
    }
    public function productions()
    {
        return $this->belongsToMany(Production::class, 'production_details')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class, 'menu_id'); // ความสัมพันธ์แบบ One-to-Many
    }


}
