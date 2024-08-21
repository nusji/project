<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'order_detail',
        'order_receipt',
        'employee_id',
    ];

    public function getTotalPriceAttribute()
    {
        $total = 0;

        foreach ($this->order_detail as $detail) {
            $total += $detail['quantity'] * $detail['price_per_unit'];
        }

        return $total;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}