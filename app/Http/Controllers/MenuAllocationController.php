<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuAllocationController extends Controller
{
    // ฟังก์ชันสำหรับแสดงรายการจัดสรรเมนู
    public function index()
    {
        // ดึงข้อมูลการจัดสรรเมนูพร้อมข้อมูลเมนู
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
        // รับข้อมูลจากฟอร์ม
        $days = $request->input('days');
        $menusPerDay = $request->input('menu_count');

        // ดึงเมนูขายดี
        $topSellers = $this->getTopSellers($request->input('fixed_top_sellers'));

        // ดึงเมนูที่เหลือ
        $availableMenus = Menu::whereNotIn('id', $topSellers->pluck('id'))
            ->inRandomOrder()
            ->get();

        // ตรวจสอบจำนวนเมนูที่เหลือว่ามีพอหรือไม่
        if ($availableMenus->count() < ($menusPerDay * $days - $topSellers->count())) {
            return redirect()->back()->with('error', 'Not enough menus to allocate.');
        }

        // จัดสรรเมนูในแต่ละวัน
        for ($day = 0; $day < $days; $day++) {
            $dailyMenus = $topSellers->merge(
                $availableMenus->splice(0, $menusPerDay - $topSellers->count())
            );

            foreach ($dailyMenus as $menu) {
                MenuAllocation::create([
                    'allocation_date' => now()->addDays($day),
                    'menu_id' => $menu->id,
                ]);
            }
        }

        return redirect()->route('allocations.index')->with('success', 'Menus allocated successfully.');
    }

    // ฟังก์ชันดึงเมนูขายดี
    protected function getTopSellers($limit = 1)
{
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
        // ดึงข้อมูลการจัดสรรเมนูพร้อมข้อมูลเมนู
        $allocation = MenuAllocation::with('menu')->findOrFail($id);

        // ดึงข้อมูล allocation details ที่เกี่ยวข้อง (ถ้ามีตาราง menu_allocation_details)
        $allocationDetails = $allocation->allocationDetails;  // ตรวจสอบความสัมพันธ์นี้ว่ามีใน model หรือไม่

        return view('allocations.show', compact('allocation', 'allocationDetails'));
    }
}
