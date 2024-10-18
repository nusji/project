<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['allocation_date', 'menu_id'];

    // ความสัมพันธ์ระหว่าง MenuAllocation กับ Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // ความสัมพันธ์ระหว่าง MenuAllocation กับ MenuAllocationDetail
    public function allocationDetails()
    {
        return $this->hasMany(MenuAllocationDetail::class);
    }
    
}
