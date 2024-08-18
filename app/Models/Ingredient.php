<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

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

    public static function isDuplicateIngredient($ingredient_name)
    {
        return self::where('ingredient_name', $ingredient_name)->exists();
    }
}
