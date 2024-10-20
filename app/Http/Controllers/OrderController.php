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
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve the search query from the request
        $search = $request->input('search');

        // Start building the query with eager loading
        $query = Order::with(['orderDetails.ingredient', 'employee'])
            ->orderByDesc('order_date');

        // If a search query is provided, add conditions to the query
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Search in the 'order_number' field
                $q->where('id', 'like', '%' . $search . '%')
                    // Or search in the employee's name
                    ->orWhereHas('employee', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    // Or search in the ingredient's name
                    ->orWhereHas('orderDetails.ingredient', function ($q) use ($search) {
                        $q->where('ingredient_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Paginate the results
        $orders = $query->paginate(20);

        // Return the view with the orders and the search query
        return view('orders.index', [
            'orders' => $orders,
            'search' => $search,
        ]);
    }


    public function create()
    {
        $ingredients = DB::table('ingredients')
            ->select('ingredients.id', 'ingredients.ingredient_name', 'ingredients.ingredient_unit', DB::raw('SUM(order_details.quantity) as order_count'))
            ->leftJoin('order_details', 'ingredients.id', '=', 'order_details.ingredient_id')
            ->groupBy('ingredients.id', 'ingredients.ingredient_name')
            ->orderByDesc('order_count') // จัดเรียงจากยอดสั่งซื้อมากไปน้อย
            ->get();
        return view('orders.create', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'order_date' => 'required|date',
                'order_detail' => 'required|string',
                'order_receipt' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'ingredients' => 'required|array',
                'ingredients.*.id' => 'required|exists:ingredients,id',
                'ingredients.*.quantity' => 'required|numeric|min:0',
                'ingredients.*.price' => 'required|numeric|min:0',


            ],
            [
                'order_receipt.required' => 'โปรดอัปโหลดไฟล์ใบเสร็จ',
                'order_receipt.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
                'order_receipt.mimes' => 'ไฟล์ที่อัปโหลดต้องเป็นไฟล์ประเภท jpeg, png, jpg, gif, svg เท่านั้น',
                'order_receipt.max' => 'ไฟล์ที่อัปโหลดต้องมีขนาดไม่เกิน 2 MB',
                'ingredients.required' => 'โปรดเลือกวัตถุดิบที่ต้องการสั่งซื้อ',
                'ingredients.*.id.required' => 'ข้อมูลวัตถุดิบไม่ถูกต้อง',
                'ingredients.*.id.exists' => 'ข้อมูลวัตถุดิบไม่ถูกต้อง',
                'ingredients.*.quantity.required' => 'โปรดกรอกจำนวนวัตถุดิบ',
                'ingredients.*.quantity.numeric' => 'จำนวนวัตถุดิบต้องเป็นตัวเลข',
                'ingredients.*.quantity.min' => 'จำนวนวัตถุดิบต้องมากกว่า 0',
                'ingredients.*.price.required' => 'โปรดกรอกราคาวัตถุดิบ',
                'ingredients.*.price.numeric' => 'ราคาวัตถุดิบต้องเป็นตัวเลข',
                'ingredients.*.price.min' => 'ราคาวัตถุดิบต้องมากกว่า 0',
                'order_date.required' => 'โปรดเลือกวันที่สั่งซื้อ',
                'order_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
                'order_detail.required' => 'โปรดกรอกรายละเอียดการสั่งซื้อ',

            ]
        );

        if (!$request->hasFile('order_receipt')) {
            return redirect()->back()->withErrors(['order_receipt' => 'โปรดอัปโหลดไฟล์ใบเสร็จ']);
        }

        DB::transaction(function () use ($request) {
            $receiptPath = $request->file('order_receipt')->store('order_receipt', 'public');
            $order = Order::create([
                'order_date' => $request->order_date,
                'order_detail' => $request->order_detail,
                'order_receipt' => $receiptPath,
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
                $ingredient->ingredient_stock += $item['quantity'];
                $ingredient->save();
            }
        });

        return redirect()->route('orders.index')->with('success', 'การสั่งซื้อถูกบันทึกเรียบร้อยแล้ว');
    }


    public function show(Order $order)
    {
        // โหลดข้อมูลที่เกี่ยวข้องพร้อมกับข้อมูลที่ถูก soft deleted เพื่อให้แสดงข้อมูลที่ถูกลบด้วย
        $order->load(['orderDetails.ingredient' => function ($query) {
            $query->withTrashed();
        }]);

        $order->orderDetails->each(function ($detail) {
            $detail->total = $detail->quantity * $detail->price;
        });

        // คำนวณราคาสุทธิทั้งหมด
        $grandTotal = $order->orderDetails->sum('total');

        return view('orders.show', compact('order', 'grandTotal'));
    }


    public function edit(Order $order)
    {
        $order->load('orderDetails.ingredient');
        $ingredients = Ingredient::all();
        return view('orders.edit', compact('order', 'ingredients'));
    }


    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'order_date' => 'required|date',
            'order_detail' => 'required|string',
            'order_receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif',
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
                    $ingredient->ingredient_stock -= $oldOrderDetail->quantity;
                }

                // สร้าง OrderDetail ใหม่
                $order->orderDetails()->create([
                    'ingredient_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // อัปเดต quantity ใหม่ของ ingredient
                $ingredient->ingredient_stock += $item['quantity'];
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
                    $ingredient->ingredient_stock -= $detail->quantity;
                    $ingredient->save();
                }
            }
            // ลบ OrderDetail ก่อนลบ Order
            $order->orderDetails()->delete();
            $order->delete();
        });

        return redirect()->route('orders.index')->with('success', 'รายการสั่งซื้อถูกลบเรียบร้อยแล้ว');
    }
    /**
     * ดึงข้อมูลวัตถุดิบที่ถูกสั่งซื้อบ่อยที่สุด (จำนวนครั้ง)
     */
    public function getTopIngredientsByCount(Request $request)
    {
        // จำนวนวัตถุดิบที่ต้องการแสดง (เช่น 5 อันดับแรก)
        $limit = $request->input('limit', 5);

        $data = OrderDetail::select(
                'ingredients.ingredient_name',
                DB::raw('COUNT(order_details.id) as order_count') // นับจำนวนครั้งที่สั่งซื้อ
            )
            ->join('ingredients', 'order_details.ingredient_id', '=', 'ingredients.id')
            ->groupBy('ingredients.ingredient_name')
            ->orderByDesc('order_count') // เรียงจากมากไปน้อยตามจำนวนครั้งที่สั่ง
            ->limit($limit)
            ->get();

        return response()->json($data);
    }

    /**
     * ดึงข้อมูลวัตถุดิบที่ถูกสั่งซื้อในปริมาณมากที่สุด (ปริมาณ)
     */
    public function getTopIngredientsByQuantity(Request $request)
    {
        // จำนวนวัตถุดิบที่ต้องการแสดง (เช่น 5 อันดับแรก)
        $limit = $request->input('limit', 5);

        $data = OrderDetail::select(
                'ingredients.ingredient_name',
                DB::raw('SUM(order_details.quantity) as total_quantity') // รวมปริมาณที่สั่งซื้อ
            )
            ->join('ingredients', 'order_details.ingredient_id', '=', 'ingredients.id')
            ->groupBy('ingredients.ingredient_name')
            ->orderByDesc('total_quantity') // เรียงจากมากไปน้อยตามปริมาณที่สั่ง
            ->limit($limit)
            ->get();

        return response()->json($data);
    }

    /**
     * ดึงข้อมูลกราฟสรุปการสั่งซื้อตามช่วงเวลา
     */
    public function getChartData(Request $request)
    {
        $period = $request->input('period', 'monthly');

        switch ($period) {
            case 'weekly':
                $data = $this->getWeeklyData();
                break;
            case 'yearly':
                $data = $this->getYearlyData();
                break;
            case 'monthly':
            default:
                $data = $this->getMonthlyData();
                break;
        }

        return response()->json($data);
    }

    /**
     * ดึงข้อมูลสรุปการสั่งซื้อรายเดือน
     */
    private function getMonthlyData()
    {
        $data = Order::select(
                DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total')
            )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $this->formatChartData($data, 'month');
    }

    /**
     * ดึงข้อมูลสรุปการสั่งซื้อรายสัปดาห์
     */
    private function getWeeklyData()
    {
        $data = Order::select(
                DB::raw('YEARWEEK(order_date, 1) as week'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total')
            )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return $this->formatChartData($data, 'week');
    }

    /**
     * ดึงข้อมูลสรุปการสั่งซื้อรายปี
     */
    private function getYearlyData()
    {
        $data = Order::select(
                DB::raw('YEAR(order_date) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total')
            )
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return $this->formatChartData($data, 'year');
    }

    /**
     * ฟอร์แมตข้อมูลสำหรับกราฟ
     */
    private function formatChartData($data, $labelKey)
    {
        $labels = $data->pluck($labelKey);
        $counts = $data->pluck('count');
        $totals = $data->pluck('total');

        return [
            'labels' => $labels,
            'counts' => $counts,
            'totals' => $totals
        ];
    }
}

