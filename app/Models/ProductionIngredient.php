<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionIngredient extends Model
{
    protected $fillable = ['production_menu_id', 'ingredient_id', 'used_quantity'];

    public function productionMenu()
    {
        return $this->belongsTo(ProductionMenu::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
