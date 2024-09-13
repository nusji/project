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
        'first_name',
        'last_name',
        'username',
        'password',
        'role',
        'id_card_number',
        'id_card_image',
        'phone_number',
        'employment_status',
        'start_date',
        'salary',

        'address',
        'date_of_birth',
        'profile_picture',
        'previous_experience',
        'relevant_education',
        'bank_account',
        'bank_account_number',
        'emergency_contact',
        'health_info',
        'religion',
        
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