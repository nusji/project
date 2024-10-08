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

    public function menus()
    {
        // กำหนดให้เชื่อมโยงผ่าน pivot table ที่ชื่อ 'menu_production' หรือใช้ชื่อ Pivot Table ที่คุณใช้
        return $this->belongsToMany(Menu::class, 'production_details')
                    ->withPivot('quantity'); // ปริมาณที่ผลิต
    }
    public function productionDetails()
    {
        return $this->hasMany(ProductionDetail::class);
    }
    
}
