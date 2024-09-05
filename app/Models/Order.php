<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_date', 'order_detail', 'order_receipt', 'employee_id'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function ingredients()
{
    return $this->belongsToMany(Ingredient::class, 'order_details')
                ->withPivot('quantity', 'price');
}
}
