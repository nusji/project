<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'bonus',
        'deductions',
        'net_salary',
        'payment_date',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateOvertimePay()
    {
        return $this->overtime_hours * $this->overtime_rate;
    }

    public function calculateNetSalary()
    {
        $overtimePay = $this->calculateOvertimePay();
        return $this->basic_salary + $overtimePay + $this->bonus - $this->deductions;
    }
}
