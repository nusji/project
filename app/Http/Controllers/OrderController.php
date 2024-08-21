<?php
// Controller: OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('employees', 'orders.employee_id', '=', 'employees.id')
            ->select('orders.*', 'employees.first_name as employee_name', DB::raw('SUM(order_details.price) as total_price'))
            ->groupBy('orders.id', 'employees.first_name')
            ->get();
        
        return view('orders.index', compact('orders'));
    }
    

    public function create()
    {
        $ingredients = Ingredient::all();
        return view('orders.create', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'order_date' => $request->order_date,
            'order_detail' => $request->order_detail,
            'order_receipt' => $request->order_receipt,
            'employee_id' => Auth::user()->id,
        ]);
        
        foreach ($request->ingredients as $ingredient) {
            $order->orderDetails()->create([
                'ingredient_id' => $ingredient['id'],
                'quantity' => $ingredient['quantity'],
                'price' => $ingredient['price'],
            ]);
        }

        return redirect()->route('orders.index');
    }

    public function show(Order $order)
    {
        $order->load('orderDetails.ingredient', 'employee');
        return view('orders.show', compact('order'));
    }


    public function edit(Order $order)
    {
        $ingredients = Ingredient::all();
        return view('orders.edit', compact('order', 'ingredients'));
    }

    public function update(Request $request, Order $order)
{
    // อัปเดตข้อมูล order
    $order->update([
        'order_date' => $request->order_date,
        'order_detail' => $request->order_detail,
        'order_receipt' => $request->order_receipt,
    ]);

    // ลบรายการ order details ที่มีอยู่เดิม
    $order->orderDetails()->delete();

    // วนลูปเพื่อสร้าง order details ใหม่
    foreach ($request->ingredients as $ingredient) {
        // ตรวจสอบว่าข้อมูล ingredient มีค่าที่ถูกต้องก่อนบันทึก
        if (!is_null($ingredient['id']) && !is_null($ingredient['quantity'])) {
            OrderDetail::create([
                'order_id' => $order->id,
                'ingredient_id' => $ingredient['id'],
                'quantity' => $ingredient['quantity'],
                'price' => $ingredient['price'],
            ]);
        }
    }

    return redirect()->route('orders.index');
}


    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }
}
