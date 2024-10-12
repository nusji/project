<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuAllocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    // ฟังก์ชันสำหรับแสดงรายการจัดสรรเมนู
    public function index()
    {
        // ดึงรายการจัดสรรเมนูทั้งหมดพร้อมข้อมูลเมนู
        $allocations = MenuAllocation::with('menu')->get();

        return view('allocations.index', compact('allocations'));
    }

    // ฟังก์ชันสำหรับแสดงฟอร์มสร้างการจัดสรรเมนูใหม่
    public function create()
    {
        return view('allocations.create');
    }

    // ฟังก์ชันสำหรับบันทึกการจัดสรรเมนูใหม่
    public function store(Request $request)
    {
        // รับค่าจำนวนวันและจำนวนเมนูต่อวันจากฟอร์ม
        $days = $request->input('days', 3);
        $menusPerDay = $request->input('menu_count', 5);

        // ดึงเมนูขายดีตามที่ผู้ใช้เลือก
        $topSellers = $this->getTopSellers($request->input('fixed_top_sellers', 1));

        // ดึงเมนูที่เหลือทั้งหมด โดยไม่รวมเมนูขายดี
        $availableMenus = Menu::whereNotIn('id', $topSellers->pluck('id'))
            ->inRandomOrder()
            ->get();

        // ตรวจสอบว่ามีเมนูเพียงพอสำหรับจัดสรรในแต่ละวัน
        if ($availableMenus->count() < ($menusPerDay * $days - $topSellers->count())) {
            return redirect()->back()->with('error', 'Not enough menus to allocate.');
        }

        // เริ่มจัดสรรเมนูโดยไม่ซ้ำกันในแต่ละวัน
        for ($day = 0; $day < $days; $day++) {
            // สร้างรายการเมนูในแต่ละวัน
            $dailyMenus = $topSellers->merge(
                $availableMenus->splice(0, $menusPerDay - $topSellers->count()) // สุ่มเมนูจากที่เหลือ
            );

            // บันทึกการจัดสรรเมนูในแต่ละวัน
            foreach ($dailyMenus as $menu) {
                MenuAllocation::create([
                    'allocation_date' => now()->addDays($day), // เพิ่มวันแต่ละรอบ
                    'menu_id' => $menu->id,
                ]);
            }
        }

        return redirect()->route('allocations.index')->with('success', 'Menus allocated successfully.');
    }

    // ฟังก์ชันดึงเมนูขายดี
    protected function getTopSellers($limit = 1)
    {
        // ดึงเมนูที่ขายหมดบ่อยที่สุด
        return DB::table('production_details')
            ->select('menu_id', DB::raw('count(*) as total_sold_out'))
            ->where('is_sold_out', true)
            ->groupBy('menu_id')
            ->orderBy('total_sold_out', 'desc')
            ->limit($limit)
            ->pluck('menu_id');
    }

    public function show($id)
    {
        // ดึงข้อมูลการจัดสรรเมนูพร้อมรายละเอียด
        $allocation = MenuAllocation::with('menu')->findOrFail($id);
        $allocationDetails = $allocation->menuAllocationDetails; // ดึงข้อมูลจาก relationship (ถ้ามี)

        return view('allocations.show', compact('allocation', 'allocationDetails'));
    }
}
