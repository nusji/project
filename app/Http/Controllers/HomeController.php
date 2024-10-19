<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Ingredient;
use App\Models\IngredientType;
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

    public function showSurvey()
    {
        // ดึงประเภทเนื้อสัตว์จากฐานข้อมูล
        $meatType = IngredientType::where('id', '1')->first();

        // ดึงวัตถุดิบที่เป็นประเภทเนื้อสัตว์
        $meats = Ingredient::where('ingredient_type_id', $meatType->id)->get();

        // ส่งข้อมูลไปยังฟอร์ม
        return view('survey_suggest', compact('meats'));
    }
}
