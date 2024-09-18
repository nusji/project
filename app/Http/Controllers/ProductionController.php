<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    // แสดงรายการการผลิตทั้งหมด
    public function index()
    {
        // ดึงข้อมูลการผลิตทั้งหมด พร้อมกับรายการเมนูที่ผลิต
        $productions = Production::withTrashed('productionDetails.menu')->get();


        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        // ดึงข้อมูลเมนูที่พร้อมขาย ทั้งหมดมาเพื่อแสดงในฟอร์ม
        $menus = Menu::where('menu_status', true)->get();
        return view('productions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_date' => 'required|date',
            'menu_list' => 'required|array',
            'menu_list.*.menu_id' => 'required|exists:menus,id',
            'menu_list.*.quantity' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // บันทึกข้อมูลการผลิต
            $production = Production::create([
                'production_date' => $request->production_date,
                'production_detail' => $request->production_detail,
            ]);

            // บันทึกเมนูที่ผลิต
            foreach ($request->menu_list as $menuData) {
                $productionDetail = ProductionDetail::create([
                    'production_id' => $production->id,
                    'menu_id' => $menuData['menu_id'],
                    'quantity' => $menuData['quantity'],
                    'quantity_sales' => 0, // ค่าเริ่มต้น
                ]);

                // ตรวจสอบวัตถุดิบและหักลบ
                $this->processIngredients($menuData['menu_id'], $menuData['quantity']);
            }
        });

        return redirect()->route('productions.create')->with('success', 'บันทึกการผลิตสำเร็จ');
    }

    private function processIngredients($menuId, $quantity)
    {
        $recipes = MenuRecipe::where('menu_id', $menuId)->get();
        $menu = Menu::find($menuId); // Assuming you have a Menu model

        foreach ($recipes as $recipe) {
            
            $requiredAmount = $recipe->amount * $quantity;
            $ingredient = Ingredient::find($recipe->ingredient_id);

            if ($ingredient->ingredient_stock < $requiredAmount) {
                return back()->with('error', 'วัตถุดิบ ' . $ingredient->ingredient_name . ' ไม่เพียงพอ');
            }

            $ingredient->update([
                'stock' => $ingredient->ingredient_stock - $requiredAmount
            ]);
        }
    }
}
