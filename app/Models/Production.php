<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_code'];

    public function productionMenus()
    {
        return $this->hasMany(ProductionMenu::class);
    }
}
