<?php
// app/Models/OrderDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'ingredient_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    
}