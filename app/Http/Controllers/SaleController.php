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
        $productions = $this->getProductionWithMenus($today);
        $menus = $this->getMenusFromProduction($productions);
        $categories = $menus->pluck('menuType')->unique();

        return view('sales.create', compact('menus', 'categories', 'today'));
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

    //ส่วนของเมธอดที่สร้างขึ้น
    public function showSoldOutManagement(Request $request, Production $production)
    {
        $productionDetails = $production->productionDetails()->with('menu')->get();
        $selectedDate = $production->production_date->format('Y-m-d');

        return view('sales.manage_sold_out', compact('productionDetails', 'selectedDate', 'production'));
    }
    public function updateSoldOutStatus(Request $request, Production $production)
    {
        $menuIds = $request->input('menu_ids', []);

        // อัปเดตสถานะ "ขายหมด" เฉพาะ ProductionDetail ของ production นี้
        $productionDetails = $production->productionDetails;

        foreach ($productionDetails as $detail) {
            $detail->is_sold_out = in_array($detail->menu_id, $menuIds);
            $detail->save();
        }

        return redirect()->route('sales.manageSoldOut', ['production' => $production->id])
            ->with('success', 'อัปเดตเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับ
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_type' => 'required|string'

        ]);

        try {
            DB::beginTransaction();

            // สร้างคำสั่งขาย (Sale)
            $sale = Sale::create([
                'payment_type' => $request->input('payment_type'),
                'sale_date' => now(),
                'employee_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);

            // สร้างรายละเอียดคำสั่งขาย (Sale Details)
            foreach ($request->input('items') as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'บันทึกการขายเรียบร้อย!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error during sale: ' . $e->getMessage()], 500);
        }
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
                ->where('is_sold_out', false) // Exclude sold-out items
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
                ];
            }),
            'date' => $date->format('Y-m-d')
        ]);
    }
}
