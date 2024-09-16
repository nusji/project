<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ingredients'; // Your table name here
    protected $primaryKey = 'id'; // ตรวจสอบว่าใช้ 'id' เป็น primary key

    protected $fillable = [
        'ingredient_name',
        'ingredient_detail',
        'ingredient_unit',
        'ingredient_quantity',
        'ingredient_type_id',
    ];

    public function ingredientType()
    {
        return $this->belongsTo(IngredientType::class);
    }

    // เพิ่มความสัมพันธ์กับ OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // เพิ่มความสัมพันธ์กับ OrderDetail แบบ many-to-many
    public function menuRecipes()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function isLowStock()
    {
        // ตรวจสอบว่าจำนวนสต็อกปัจจุบันน้อยกว่าหรือเท่ากับปริมาณขั้นต่ำ
        return $this->ingredient_quantity <= $this->minimum_quantity;
    }
}
