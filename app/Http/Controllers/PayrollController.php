<?php
// app/Http/Controllers/PayrollController.php
namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // รับข้อมูลผู้ใช้ที่ล็อกอินอยู่
        $search = $request->input('search'); // รับค่าการค้นหา

        // รับค่าเดือนและปีจาก request หรือใช้เดือนปัจจุบันและปีปัจจุบันเป็นค่าเริ่มต้น
        $currentMonth = $request->input('month', now()->month);
        $currentYear = $request->input('year', now()->year);

        if ($user->role === 'owner') {
            // สำหรับเจ้าของ: แสดงข้อมูลทั้งหมด
            // เริ่มต้น Query สำหรับ Payrolls ทั้งหมดในเดือนและปีที่เลือก
            $payrollsQuery = Payroll::with('employee')
                ->whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear);

            // ถ้ามีการค้นหา ให้เพิ่มเงื่อนไขการค้นหา
            if ($search) {
                $payrollsQuery->whereHas('employee', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('id_card_number', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%')
                        ->orWhere('employment_type', 'like', '%' . $search . '%')
                        ->orWhere('id', 'like', '%' . $search . '%');
                });
            }

            // เรียงลำดับและแบ่งหน้า
            $payrolls = $payrollsQuery->orderBy('id', 'desc')->paginate(20);

            // จำนวนพนักงานทั้งหมด
            $totalEmployees = Employee::count();

            // คำนวณยอดเงินเดือนที่จ่ายทั้งหมดในเดือนนี้
            $totalPaidMonth = Payroll::whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear)
                ->sum('net_salary');

            // ดึงรายชื่อพนักงานที่ยังไม่ได้รับเงินเดือนในเดือนปัจจุบันและมีบทบาทเป็นพนักงาน
            $unpaidEmployees = Employee::where('role', 'employee')
                ->whereDoesntHave('payrolls', function ($query) use ($currentMonth, $currentYear) {
                    $query->whereMonth('payment_date', $currentMonth)
                        ->whereYear('payment_date', $currentYear);
                })->get();

            return view('payrolls.index', compact('payrolls', 'totalEmployees', 'totalPaidMonth', 'unpaidEmployees', 'currentMonth', 'currentYear', 'search'));
        } elseif ($user->role === 'employee') {
            // สำหรับพนักงาน: แสดงข้อมูลเฉพาะของตนเอง

            // สมมติว่าผู้ใช้มีความสัมพันธ์กับพนักงาน (User -> Employee)
            $employee = $user;

            // ดึง Payrolls ของพนักงานคนนั้นในเดือนและปีที่เลือก
            $payrolls = Payroll::where('employee_id', $employee->id)
                ->whereMonth('payment_date', $currentMonth)
                ->whereYear('payment_date', $currentYear)
                ->orderBy('payment_date', 'desc')
                ->paginate(10);

            return view('payrolls.index', compact('payrolls', 'currentMonth', 'currentYear'));
        } else {
            // ถ้าผู้ใช้มีบทบาทอื่นหรือไม่ได้รับอนุญาต
            abort(403, 'Unauthorized action.');
        }
    }


    public function create()
    {
        $employees = Employee::where('role', 'employee')->get();
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
            'slip_image' => 'required|file|mimes:jpeg,png,pdf',
            'payment_method' => 'required|string',
        ]);

        if ($request->hasFile('slip_image')) {
            $validatedData['slip_image'] = $request->file('slip_image')->store('slip_image', 'public');
        }

        Payroll::create($validatedData);

        // No additional actions needed here

        return redirect()->route('payrolls.index')->with('success', 'บันทึกการจ่ายเงินเดือนเรียบร้อยแล้ว');
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

        return redirect()->route('payrolls.index')->with('success', 'แก้ไขการจ่ายเงินเดือนเรียบร้อยแล้ว');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'ลบการจ่ายเงินเดือนเรียบร้อยแล้ว');
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
