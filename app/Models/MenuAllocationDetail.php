<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAllocationDetail extends Model
{
    use HasFactory;

    protected $fillable = ['menu_allocation_id', 'menu_id', 'production_quantity'];

    protected $attributes = [
        'production_quantity' => 1,
    ];

    public function menuAllocation()
    {
        return $this->belongsTo(MenuAllocation::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function productionDetails() {
        return $this->hasMany(ProductionDetail::class, 'menu_id', 'menu_id');
    }
}
