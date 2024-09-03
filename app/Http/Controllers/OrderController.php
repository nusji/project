<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // แสดงรายการ Order ทั้งหมด
    public function index()
    {
        // ดึงข้อมูลรายการสั่งซื้อพร้อมรายละเอียดและวัตถุดิบ
        $orders = Order::with('orderDetails.ingredient')->get();
        // สร้างตัวแปรเพื่อเก็บข้อมูลของแต่ละรายการสั่งซื้อ
        $orderSummaries = $orders->map(function ($order) {
            // คำนวณราคารวมของรายการสั่งซื้อ
            $totalPrice = $order->orderDetails->sum('price');
            // นับจำนวนวัตถุดิบในรายการสั่งซื้อ
            $ingredientCount = $order->orderDetails->count();
            return [
                'order' => $order,
                'totalPrice' => $totalPrice,
                'ingredientCount' => $ingredientCount
            ];
        });
        // ส่งข้อมูลไปที่ view
        return view('orders.index', ['orderSummaries' => $orderSummaries]);
    }
    

    // แสดงฟอร์มสำหรับสร้าง Order
    public function create()
    {
        $ingredients = Ingredient::all();
        return view('orders.create', compact('ingredients'));
    }

    // บันทึก Order ลงในฐานข้อมูลตาราง orders และ order_details
    public function store(Request $request)
    {
        $request->validate([
            'order_date' => 'required|date',
            'order_detail' => 'required|string',
            'order_receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // กำหนดให้เป็นไฟล์รูปภาพ
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // จัดการการอัปโหลดไฟล์รูปภาพ
            $receiptPath = $request->file('order_receipt')->store('order_receipt', 'public'); // บันทึกไฟล์รูปภาพไปที่ 'storage/app/public/receipts'
            $order = Order::create([
                'order_date' => $request->order_date,
                'order_detail' => $request->order_detail,
                'order_receipt' => $receiptPath, // บันทึกเส้นทางของไฟล์รูปภาพ
                'employee_id' => Auth::id(),
            ]);

            foreach ($request->ingredients as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'ingredient_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $ingredient = Ingredient::find($item['id']);
                $ingredient->ingredient_quantity += $item['quantity'];
                $ingredient->save();
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    // แสดงรายละเอียดของ Order และรายการ OrderDetail ที่เกี่ยวข้อง
    public function show(Order $order)
    {
        // Load the related orderDetails and ingredients
        $order->load('orderDetails.ingredient', 'employee');

        // Calculate the total price for each order detail and the grand total
        $order->orderDetails->each(function ($detail) {
            $detail->total = $detail->quantity * $detail->price;
        });

        $grandTotal = $order->orderDetails->sum('total');

        return view('orders.show', compact('order', 'grandTotal'));
    }

    // แสดงฟอร์มสำหรับแก้ไข Order
    public function edit(Order $order)
    {
        $order->load('orderDetails.ingredient');
        $ingredients = Ingredient::all();
        return view('orders.edit', compact('order', 'ingredients'));
    }

    //
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'order_date' => 'required|date',
            'order_detail' => 'required|string',
            'order_receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.price' => 'required|numeric|min:0',
        ]);
    
        DB::transaction(function () use ($request, $order, $validatedData) {
            if ($request->hasFile('order_receipt')) {
                // ลบไฟล์เก่า
                if ($order->order_receipt) {
                    Storage::disk('public')->delete($order->order_receipt);
                }
                $receiptPath = $request->file('order_receipt')->store('order_receipt', 'public');
            } else {
                $receiptPath = $order->order_receipt;
            }
    
            $order->update([
                'order_date' => $validatedData['order_date'],
                'order_detail' => $validatedData['order_detail'],
                'order_receipt' => $receiptPath,
            ]);
    
            // ลบ OrderDetail เดิมที่เกี่ยวข้องกับ Order นี้
            $order->orderDetails()->delete();
    
            foreach ($request->ingredients as $item) {
                $ingredient = Ingredient::find($item['id']);
                
                // คืนค่า quantity เก่ากลับไปก่อนจะคำนวณค่าใหม่
                $oldOrderDetail = $order->orderDetails()->where('ingredient_id', $item['id'])->first();
                if ($oldOrderDetail) {
                    $ingredient->quantity -= $oldOrderDetail->quantity;
                }
    
                // สร้าง OrderDetail ใหม่
                $order->orderDetails()->create([
                    'ingredient_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
    
                // อัปเดต quantity ใหม่ของ ingredient
                $ingredient->quantity += $item['quantity'];
                $ingredient->save();
            }
    
        });
    
        return redirect()->route('orders.show', $order)->with('success', 'รายการสั่งซื้อถูกอัปเดตเรียบร้อยแล้ว');
    }
    

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->orderDetails as $detail) {
                $ingredient = $detail->ingredient;
                // ตรวจสอบว่า $ingredient มีคอลัมน์ quantity หรือไม่
                if ($ingredient) {
                    $ingredient->ingredient_quantity -= $detail->quantity;
                    $ingredient->save();
                }
            }
            // ลบ OrderDetail ก่อนลบ Order
            $order->orderDetails()->delete();
            $order->delete();
        });
    
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    
}
