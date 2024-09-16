<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'id';
    protected $table = "employees";
    protected $fillable = [
        'name',
        'id_card_number',
        'phone_number',
        'employment_type',
        'username',
        'password',
        'salary',
        'start_date',
        'role',

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