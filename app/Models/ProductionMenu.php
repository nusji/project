<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMenu extends Model
{
    protected $fillable = ['production_id', 'menu_id', 'produced_quantity', 'selling_quantity'];

    public function productionIngredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
