<?php
namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Menu;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::latest()->paginate(10);
        return view('productions.index', compact('productions'));
    }
    public function create()
    {
        // ดึงเมนูทั้งหมดที่ยังสามารถผลิตได้
        $menus = Menu::where('menu_status', true)->get();
        return view('productions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'production_date' => 'required|date',
            'production_detail' => 'nullable|string',
            'menus' => 'required|array',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|integer|min:1',
        ]);

        // ตรวจสอบวัตถุดิบก่อนการผลิต
        foreach ($data['menus'] as $menuData) {
            $menu = Menu::find($menuData['menu_id']);
            foreach ($menu->recipes as $recipe) {
                $requiredAmount = $menuData['quantity'] * $recipe->amount;
                if ($recipe->ingredient->stock < $requiredAmount) {
                    return back()->withErrors(['error' => 'วัตถุดิบไม่เพียงพอสำหรับเมนู ' . $menu->menu_name]);
                }
            }
        }

        // เริ่มการบันทึกข้อมูลการผลิต
        $production = Production::create([
            'production_date' => $data['production_date'],
            'production_detail' => $data['production_detail'],
        ]);

        foreach ($data['menus'] as $menuData) {
            $menu = Menu::find($menuData['menu_id']);
            $quantitySales = $menuData['quantity'] * 10;

            // บันทึกรายละเอียดการผลิต
            $production->details()->create([
                'menu_id' => $menuData['menu_id'],
                'quantity' => $menuData['quantity'],
                'quantity_sales' => $quantitySales,
            ]);

            // หักลบวัตถุดิบ
            foreach ($menu->recipes as $recipe) {
                $requiredAmount = $menuData['quantity'] * $recipe->amount;
                $recipe->ingredient->decrement('stock', $requiredAmount);
            }
        }

        return redirect()->route('productions.index')->with('success', 'บันทึกการผลิตเรียบร้อยแล้ว');
    }

    public function destroy(Production $production)
    {
        // คืนค่าวัตถุดิบที่ถูกหักไป
        foreach ($production->details as $detail) {
            $menu = $detail->menu;
            foreach ($menu->recipes as $recipe) {
                $restoredAmount = $detail->quantity * $recipe->amount;
                $recipe->ingredient->increment('stock', $restoredAmount);
            }
        }

        $production->delete();
        return redirect()->route('productions.index')->with('success', 'ลบการผลิตเรียบร้อยแล้ว และคืนวัตถุดิบสำเร็จ');
    }
}
