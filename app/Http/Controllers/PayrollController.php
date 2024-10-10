<?php
// app/Http/Controllers/PayrollController.php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PayrollController extends Controller
{
    public function index(Request $request)
    {
        // รับค่าเดือนและปีจาก request หรือใช้เดือนปัจจุบันและปีปัจจุบันเป็นค่าเริ่มต้น
        $currentMonth = $request->input('month', now()->month);
        $currentYear = $request->input('year', now()->year);
    
        $payrolls = Payroll::with('employee')
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->paginate(10);
    
        $totalEmployees = Employee::count(); // จำนวนพนักงานทั้งหมด
    
        // คำนวณยอดเงินเดือนที่จ่ายทั้งหมดในเดือนนี้
        $totalPaidMonth = Payroll::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('net_salary');
    
        // ดึงรายชื่อพนักงานที่ยังไม่ได้รับเงินเดือนในเดือนปัจจุบัน
        $unpaidEmployees = Employee::whereDoesntHave('payrolls', function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear);
        })->get();
    
        return view('payrolls.index', compact('payrolls', 'totalEmployees', 'totalPaidMonth', 'unpaidEmployees', 'currentMonth', 'currentYear'));
    }
    

    public function create()
    {
        $employees = Employee::all();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'slip' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'payment_channel' => 'required|string',
        ]);
    
        if ($request->hasFile('slip')) {
            $validatedData['slip'] = $request->file('slip')->store('slips', 'public');
        }
    
        Payroll::create($validatedData);
    
        return redirect()->route('payrolls.index')->with('success', 'Payroll record created successfully.');
    }
    

    public function show(Payroll $payroll)
    {
        return view('payrolls.show', compact('payroll'));
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);

        // Convert the payment_date to a Carbon instance
        $payroll->payment_date = Carbon::parse($payroll->payment_date);

        $employees = Employee::all();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $payroll->update($validatedData);

        return redirect()->route('payrolls.index')->with('success', 'Payroll record updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'Payroll record deleted successfully.');
    }

    public function getUnpaidEmployeesForCurrentMonth()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // ดึงพนักงานที่ไม่ได้รับเงินเดือนในเดือนปัจจุบัน
        $unpaidEmployees = Employee::whereDoesntHave('payrolls', function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear)
                ->where('payment_status', 'Paid');
        })->get();

        return view('payrolls.index', compact('unpaidEmployees'));
    }
}
