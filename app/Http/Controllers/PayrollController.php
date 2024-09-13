<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee')->orderBy('payment_date', 'desc')->paginate(10);
        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'payment_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->employee_ids as $employeeId) {
                $employee = Employee::findOrFail($employeeId);
                $baseSalary = $employee->base_salary;
                $bonus = $request->input("bonus.$employeeId", 0);
                $deductions = $request->input("deductions.$employeeId", 0);
                $netSalary = $baseSalary + $bonus - $deductions;

                Payroll::create([
                    'employee_id' => $employeeId,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'payment_date' => $request->payment_date,
                ]);
            }
        });

        return redirect()->route('payrolls.index')->with('success', 'Payrolls created successfully.');
    }

    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'bonus' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $baseSalary = $payroll->employee->base_salary;
        $netSalary = $baseSalary + $request->bonus - $request->deductions;

        $payroll->update([
            'bonus' => $request->bonus,
            'deductions' => $request->deductions,
            'net_salary' => $netSalary,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('payrolls.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    public function printSlip(Payroll $payroll)
    {
        $pdf = PDF::loadView('payrolls.slip', compact('payroll'));
        return $pdf->download('salary_slip.pdf');
    }
}