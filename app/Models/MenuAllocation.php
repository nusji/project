<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['allocation_date'];

    public function menuAllocationDetails()
    {
        return $this->hasMany(MenuAllocationDetail::class);
    }
}
