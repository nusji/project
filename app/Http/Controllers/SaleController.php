<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Production;
use App\Models\ProductionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        // รับค่าการค้นหาและการเรียงลำดับจากคำขอ
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'sale_date'); // ค่าเริ่มต้นคือ 'sale_date'
        $sortOrder = $request->input('sort_order', 'desc'); // ค่าเริ่มต้นคือ 'desc'

        // ดึงข้อมูลจากฐานข้อมูลพร้อมกับเงื่อนไขการค้นหาและการเรียงลำดับ
        $sales = Sale::with(['employee', 'saleDetails.menu'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                        ->orWhere('sale_date', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('payment_type', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10);

        $todaySalesData = DB::table('sale_details')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id') // เชื่อมกับตาราง menus
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id') // เชื่อมกับตาราง sales
            ->select(
                DB::raw('SUM(sale_details.quantity * menus.menu_price) as total_revenue_today'), // ยอดรวมรายรับ
                DB::raw('COUNT(sales.id) as total_sales_today') // จำนวนครั้งการขาย
            )
            ->whereDate('sales.sale_date', Carbon::today()) // เงื่อนไข: วันที่เป็นวันนี้
            ->first(); // ดึงผลลัพธ์ชุดแรก

        $todaySalesByPaymentMethod = DB::table('sale_details')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->select(
                'sales.payment_type', // เพิ่มคอลัมน์ payment_type
                DB::raw('SUM(sale_details.quantity * menus.menu_price) as total_revenue'), // เปลี่ยนชื่อเป็น total_revenue
                DB::raw('COUNT(sales.id) as total_sales') // เปลี่ยนชื่อเป็น total_sales
            )
            ->whereDate('sales.sale_date', Carbon::today())
            ->groupBy('sales.payment_type')
            ->get(); // เปลี่ยนเป็น get() เพื่อดึงข้อมูลทั้งหมด


        // ส่งค่าที่จำเป็นไปยัง View
        return view('sales.index', compact('sales', 'search', 'sortBy', 'sortOrder', 'todaySalesData', 'todaySalesByPaymentMethod'));
    }

    public function create()
    {
        $today = Carbon::today();
        $productions = $this->getProductionWithMenus($today); // ฟังก์ชันสำหรับดึงการผลิตเมนูของวันนี้
        $menus = $this->getMenusFromProduction($productions); // ดึงข้อมูลเมนูที่ถูกผลิตในวันนี้
        $categories = $menus->pluck('menuType')->unique(); // ดึงประเภทเมนู
    
        // คำนวณยอดคงเหลือสำหรับแต่ละเมนู
        foreach ($menus as $menu) {
            $menu->total_remaining_amount = $menu->productionDetails->sum('remaining_amount');
            $menu->is_sold_out = $menu->total_remaining_amount <= 0; // ตรวจสอบว่าหมดหรือไม่
        }
    
        // ส่งข้อมูลไปยัง view
        return view('sales.create', compact('menus', 'categories', 'today'));
    }
    


    public function store(Request $request)
    {
        // Validate the received data
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_type' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Create the sale
            $sale = Sale::create([
                'payment_type' => $request->input('payment_type'),
                'sale_date' => now(),
                'employee_id' => Auth::user()->id,
            ]);

            // For each item in the sale
            foreach ($request->input('items') as $item) {
                // Create sale detail
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);

                // Get the menu item
                $menu = Menu::find($item['id']);

                // Get portion_size
                $portionSize = $menu->portion_size;

                // Calculate total amount to subtract
                $amountToSubtract = $portionSize * $item['quantity'];

                // Get the production detail(s) for this menu item
                $productionDetails = ProductionDetail::where('menu_id', $menu->id)
                    ->where('remaining_amount', '>', 0)
                    ->orderBy('created_at') // Adjust ordering if necessary
                    ->get();

                // Calculate total available amount
                $totalAvailableAmount = $productionDetails->sum('remaining_amount');

                if ($totalAvailableAmount <= 0) {
                    // No stock available at all
                    throw new \Exception('ไม่มีสินค้าเหลือสำหรับเมนู: ' . $menu->menu_name);
                }

                $remainingToSubtract = $amountToSubtract;

                foreach ($productionDetails as $productionDetail) {
                    if ($remainingToSubtract <= 0) {
                        break;
                    }

                    // Available amount in this production detail
                    $availableAmount = $productionDetail->remaining_amount;

                    if ($availableAmount >= $remainingToSubtract) {
                        // Subtract the required amount
                        $productionDetail->remaining_amount -= $remainingToSubtract;
                        $remainingToSubtract = 0;

                        // If remaining_amount is zero or less, set is_sold_out to true
                        if ($productionDetail->remaining_amount <= 0) {
                            $productionDetail->remaining_amount = 0;
                            $productionDetail->is_sold_out = true;
                        }

                        $productionDetail->save();
                    } else {
                        // Subtract all available amount and continue to next production detail
                        $remainingToSubtract -= $availableAmount;
                        $productionDetail->remaining_amount = 0;
                        $productionDetail->is_sold_out = true;
                        $productionDetail->save();
                    }
                }

                // At this point, $remainingToSubtract may be greater than zero
                // If totalAvailableAmount was less than amountToSubtract, we proceed anyway
                // and set remaining amounts to zero

                // Optionally, you can log or notify that the stock was insufficient but sale proceeded
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'บันทึกการขายเรียบร้อย!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการขาย: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // ดึงข้อมูลคำสั่งขาย และรายละเอียดคำสั่งขายที่เกี่ยวข้อง
        $sale = Sale::with('saleDetails.menu')->findOrFail($id);

        // ส่งข้อมูลไปยัง view
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'ลบรายการขายเรียบร้อยแล้ว');
    }





    private function getProductionWithMenus($date)
    {
        return Production::whereDate('production_date', $date)
            ->with('productionDetails.menu.menuType')
            ->get();
    }

    private function getMenusFromProduction($productions)
    {
        return $productions->flatMap(function ($production) {
            return $production->productionDetails
                ->map(function ($detail) {
                    return $detail->menu;
                });
        })->unique('id');
    }

    public function getMenusByDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        $productions = $this->getProductionWithMenus($date);
        $menus = $this->getMenusFromProduction($productions);

        if ($productions->isEmpty()) {
            return response()->json([
                'menus' => [],
                'date' => $date->format('Y-m-d'),
                'message' => 'ไม่มีข้อมูลเมนูสำหรับวันที่เลือก'
            ]);
        }

        return response()->json([
            'menus' => $menus->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'menu_name' => $menu->menu_name,  // เปลี่ยน key เป็น 'menu_name'
                    'menu_price' => $menu->menu_price, // เปลี่ยน key เป็น 'menu_price'
                    'menu_image' => $menu->menu_image ? Storage::url($menu->menu_image) : null,
                    'menu_type_id' => $menu->menu_type_id,
                    'menu_type_name' => $menu->menuType->menu_type_name, // เพิ่ม key 'menu_type_name'
                    'portion_size' => $menu->portion_size,
                    'total_remaining_amount' => $menu->productionDetails->sum('remaining_amount')
                ];
            }),
            'date' => $date->format('Y-m-d')
        ]);
    }

    public function updateSoldOutStatus(Request $request)
    {
        $soldOutMenus = $request->input('sold_out_menus', []);

        // ตั้งค่า is_sold_out = true สำหรับเมนูที่ถูกติ้ก
        foreach ($soldOutMenus as $menuId => $value) {
            Menu::where('id', $menuId)->update(['is_sold_out' => true]);
        }

        // ตั้งค่า is_sold_out = false สำหรับเมนูที่ไม่ได้ถูกติ้ก
        Menu::whereNotIn('id', array_keys($soldOutMenus))->update(['is_sold_out' => false]);

        return response()->json(['status' => 'success']);
    }
}
