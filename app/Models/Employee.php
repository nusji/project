<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Authenticatable
{
    //latest
    use HasFactory, Notifiable, SoftDeletes;
    protected $table = "employees";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'id_card_number',
        'employee_type',
        'start_date',
        'salary',
        'phone_number',
        'address',
        'date_of_birth',
        'profile_picture',
        'bank_account',
        'bank_account_number',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship with Payroll
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}