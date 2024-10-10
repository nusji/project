<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    use HasFactory;
    protected $table = 'production_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'production_id',
        'menu_id',
        'quantity',
        'is_sold_out', // Add this line
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class)->withTrashed();
    }
    
}
