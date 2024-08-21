<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'menu_id',
        'quantity',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}