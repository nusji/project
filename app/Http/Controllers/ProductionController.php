<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Livewire\ProductionComponent;

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
        return view('productions.create');
    }

    public function store(Request $request)
    {
        $productionComponent = new ProductionComponent();
        $productionComponent->productionDate = $request->input('production_date');
        $productionComponent->productionDetail = $request->input('production_detail');
        $productionComponent->menuList = $request->input('menu_list');

        // คำนวณ quantity_sales สำหรับแต่ละเมนู
        foreach ($productionComponent->menuList as &$menu) {
            $menu['quantity_sales'] = $menu['quantity'] * 10;
        }

        $result = $productionComponent->saveProduction();

        if ($result['success']) {
            return redirect()->route('productions.index')->with('success', 'บันทึกการผลิตเรียบร้อยแล้ว');
        } else {
            return redirect()->back()->withErrors($result['errors'])->withInput();
        }
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
