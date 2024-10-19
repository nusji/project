<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Ingredient;
use App\Models\IngredientType;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\MenuRecipe;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // แสดงเมนูที่ผลิตในวันนี้ ในฝั่งของคนดู
    public function showWelcomePage()
    {
        // กำหนดวันที่วันนี้
        $today = now()->startOfDay();

        // ดึงข้อมูลจากตาราง production โดยใช้ created_at จากตาราง production
        $productions = Production::whereDate('created_at', $today)
            ->with(['productionDetails.menu'])  // ดึงข้อมูล production_details พร้อมกับเมนู
            ->get();

        // สร้างคอลเลกชันของเมนูจาก productionDetails
        $menus = $productions->map(function ($production) {
            return $production->productionDetails->map(function ($detail) {
                $menu = $detail->menu;
                // ตรวจสอบว่ามีการขายหมดหรือไม่และกำหนด flag
                $menu->is_sold_out = $detail->is_sold_out;
                return $menu;
            });
        })->flatten();  // แบนข้อมูลให้อยู่ในรูปแบบคอลเลกชันของเมนู

        // ตรวจสอบว่าเมนูมีค่าของ menu_image และเขียน log เมื่อไม่พบ
        foreach ($menus as $menu) {
            if (empty($menu->menu_image)) {
                Log::warning('Menu ' . $menu->menu_name . ' does not have an image.');
            }
        }

        return view('welcome', compact('menus'));
    }

    public function showMenu()
    {
        $today = now()->startOfDay();
    
        // ดึงข้อมูล production พร้อมรายละเอียดและประเภทเมนู
        $productions = Production::whereDate('created_at', $today)
            ->with(['productionDetails.menu.menuType'])  // ดึงข้อมูลประเภทเมนู
            ->get();
    
        $menus = $productions->map(function ($production) {
            return $production->productionDetails->map(function ($detail) {
                $menu = $detail->menu;
                $menu->is_sold_out = $detail->is_sold_out;
                $menu->ramaining_amount = $detail->remaining_amount;
                return $menu;
            });
        })->flatten();

        foreach ($menus as $menu) {
            if (empty($menu->menu_image)) {
                Log::warning('Menu ' . $menu->menu_name . ' does not have an image.');
            }
        }
    
        return view('menu-today', compact('menus'));
    }
    

    public function showSurvey()
    {
        // ดึงประเภทเนื้อสัตว์จากฐานข้อมูล
        $meatType = IngredientType::where('id', '1')->first();
        $menuType = menuType::all();

        // ดึงวัตถุดิบที่เป็นประเภทเนื้อสัตว์
        $meats = Ingredient::where('ingredient_type_id', $meatType->id)->get();

        // ส่งข้อมูลไปยังฟอร์ม
        return view('survey_suggest', compact('meats', 'menuType'));
    }

    public function queryMenus(Request $request)
    {
        try {
            // รับค่าจากฟอร์ม
            $flavor = $request->input('flavor_preference');
            $meat = $request->input('meat_preference');
            $foodType = $request->input('food_type_preference');

            // ตรวจสอบวัตถุดิบ
            $ingredient = Ingredient::find($meat);

            if (!$ingredient && $meat !== 'none') {
                return response()->json(['success' => false, 'message' => 'ไม่พบวัตถุดิบที่เลือก']);
            }

            // ค้นหาเมนูที่ใช้วัตถุดิบนี้จากตาราง menu_recipes
            $menuIds = MenuRecipe::where('ingredient_id', $ingredient->id)
                ->pluck('menu_id');

            // ค้นหาเมนูที่ตรงตามรสชาติและประเภทอาหารที่เลือก
            $menusQuery = Menu::where('menu_type_id', $foodType); // ตรวจสอบประเภทเมนู

            // หากเลือก "ไม่กินเนื้อสัตว์"
            if ($meat === 'none') {
                // ค้นหาเมนูที่ไม่มีวัตถุดิบประเภทเนื้อสัตว์
                $menusQuery->whereDoesntHave('menuRecipes', function ($query) {
                    $query->whereHas('ingredient', function ($subQuery) {
                        $subQuery->where('ingredient_type_id', 1); // สมมติว่า 1 คือประเภทเนื้อสัตว์
                    });
                });
            } else {
                // ตรวจสอบว่า flavor มีค่าเป็น "ทั้งหมด" ให้แสดงทุกรสชาติ
                if ($flavor !== 'ทั้งหมด') {
                    $menusQuery->where('menu_taste', $flavor);
                }

                // ใช้รหัสเมนูที่มีวัตถุดิบที่เลือก
                $menusQuery->whereIn('id', $menuIds);
            }

            $menus = $menusQuery->get();

            // ถ้ามีเมนูที่ตรงกับเงื่อนไข
            if ($menus->count() > 0) {
                return response()->json(['success' => true, 'menus' => $menus]);
            }

            // ถ้าไม่พบเมนูที่ตรงกับเงื่อนไข
            return response()->json(['success' => false, 'message' => 'ไม่พบเมนูที่ตรงกับเงื่อนไข']);
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function showAboutPage()
    {
        return view('about');
    }
}
