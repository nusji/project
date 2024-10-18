<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks'; // Your table name here
    protected $primaryKey = 'id'; // ตรวจสอบว่าใช้ 'id' เป็น primary key
    protected $fillable = ['menu_id', 'rating', 'comment'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
