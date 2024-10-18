<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngredientType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ingredient_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_type_name',
        'ingredient_type_detail',
    ];
    protected $dates = [
        'deleted_at',
        
    ];

    // เพิ่มความสัมพันธ์กับ Ingredient
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
