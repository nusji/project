<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use SoftDeletes;
    protected $table = 'productions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'production_date',
        'production_detail',
    ];

    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class, 'production_id', 'id');
    }
}
