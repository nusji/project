<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientType extends Model
{
    use HasFactory;

    protected $table = 'ingredients_type';

    protected $fillable = [
        'ingredient_type_name',
        'ingredient_type_detail',
    ];
}
