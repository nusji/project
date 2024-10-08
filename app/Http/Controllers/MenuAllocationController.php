<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAllocation;
use App\Models\MenuAllocationDetail;
use Illuminate\Http\Request;

class MenuAllocationController extends Controller
{
    public function allocateMenus(Request $request)
    {
        // จำนวนเมนูขายดีที่ต้องการ
        $topCount = $request->input('top_count', 5); // default 5
        // จำนวนเมนูที่ต้องการสุ่ม
        $randomCount = $request->input('random_count', 3); // default 3

        // ดึงเมนูขายดี
        $topMenus = Menu::withCount('sales')
            ->orderBy('sales_count', 'desc')
            ->take($topCount)
            ->get();

        // ดึงเมนูที่ไม่ซ้ำกับสัปดาห์ก่อน
        $lastWeekMenus = MenuAllocationDetail::whereHas('menuAllocation', function ($query) {
            $query->whereBetween('allocation_date', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()]);
        })->pluck('menu_id');

        // สุ่มเมนูที่ไม่ซ้ำกับเมนูของสัปดาห์ก่อน
        $randomMenus = Menu::whereNotIn('id', $lastWeekMenus)
            ->inRandomOrder()
            ->take($randomCount)
            ->get();

        // สร้างข้อมูลการจัดสรรเมนู
        $allocation = MenuAllocation::create([
            'allocation_date' => now(),
        ]);

        // บันทึกเมนูขายดี
        foreach ($topMenus as $menu) {
            $allocation->menuAllocationDetails()->create([
                'menu_id' => $menu->id,
            ]);
        }

        // บันทึกเมนูสุ่ม
        foreach ($randomMenus as $menu) {
            $allocation->menuAllocationDetails()->create([
                'menu_id' => $menu->id,
            ]);
        }

        return response()->json([
            'message' => 'Menu allocation completed successfully!',
            'allocation' => $allocation->load('menuAllocationDetails'),
        ]);
    }
}
