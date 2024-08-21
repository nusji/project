<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_date',
        'comment',
    ];

    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class);
    }
}