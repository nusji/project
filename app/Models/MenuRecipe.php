<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRecipe extends Model
{
    use HasFactory;
    protected $table = 'menu_recipes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'menu_id',
        'ingredient_id',
        'amount',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}