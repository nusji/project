<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    //latest
    use HasFactory, SoftDeletes;
    protected $table = 'ingredients'; // Your table name here
    protected $primaryKey = 'id'; // ตรวจสอบว่าใช้ 'id' เป็น primary key
    protected $fillable = [
        'ingredient_name',
        'ingredient_unit',
        'ingredient_stock',
        'minimum_quantity',
        'ingredient_type_id',
    ];
    protected $dates = [
        'deleted_at',

    ];

    // ในโมเดล Ingredient
    public function ingredientType()
    {
        return $this->belongsTo(IngredientType::class, 'ingredient_type_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'ingredient_id');
    }
    public function menuRecipes()
    {
        return $this->hasMany(Ingredient::class, 'ingredient_id');
    }

    public function menus()
    {
        // ใช้ 'menu_recipes' เป็นชื่อ Pivot Table
        return $this->belongsToMany(Menu::class, 'menu_recipes')
                    ->withPivot('quantity_required');
    }

    public function isLowStock()
    {
        // ตรวจสอบว่าจำนวนสต็อกปัจจุบันน้อยกว่าหรือเท่ากับปริมาณขั้นต่ำ
        return $this->ingredient_quantity <= $this->minimum_quantity;
    }
}
