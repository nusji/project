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


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('employee', 'saleDetails.menu')
            ->orderBy('sale_date', 'desc')
            ->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // หาวันปัจจุบัน
        $today = Carbon::today();
        // ดึงข้อมูล production ของวันนี้ พร้อมกับรายละเอียดเมนู
        $productions = Production::whereDate('production_date', $today)
            ->with('productionDetails.menu.menuType') // เพิ่ม category ในการ eager load
            ->get();

        // รวมเมนูจาก production_details ของวันนี้
        $menus = $productions->flatMap(function ($production) {
            return $production->productionDetails->map(function ($detail) {
                return $detail->menu;
            });
        })->unique('id');

        // ดึงประเภทเมนูทั้งหมดที่มีในเมนูวันนี้
        $categories = $menus->pluck('menuType')->unique();

        // ส่งข้อมูลไปยัง view
        return view('sales.create', compact('menus', 'categories'));
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

            return response()->json(['success' => true, 'message' => 'Sale completed successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error during sale: ' . $e->getMessage()], 500);
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
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully!');
    }
}
