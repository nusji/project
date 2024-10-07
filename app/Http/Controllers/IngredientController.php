<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use App\Notifications\LowStockNotification;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        // เริ่มต้น query โดยรวมความสัมพันธ์ ingredientType
        $query = Ingredient::with('ingredientType');

        // ตรวจสอบว่ามีการค้นหาหรือไม่
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ingredient_name', 'like', "%{$search}%")
                ->orWhereHas('ingredientType', function ($q) use ($search) {
                    $q->where('ingredient_type_name', 'like', "%{$search}%"); // สมมติว่า ingredient type มีชื่อว่า 'ingredient_type_name'
                });
        }

        // ตรวจสอบว่ามีการจัดเรียงหรือไม่
        if ($request->has('orderBy') && $request->has('direction')) {
            $orderBy = $request->input('orderBy');
            $direction = $request->input('direction');
            $query->orderBy($orderBy, $direction); // จัดเรียงตามตัวเลือกที่ผู้ใช้กำหนด
        } else {
            // กำหนดค่าเริ่มต้นการจัดเรียง
            $query->orderBy('ingredient_name', 'asc'); // เรียงตามชื่อวัตถุดิบเป็นค่าเริ่มต้น
        }

        // ดึงข้อมูลวัตถุดิบพร้อมแบ่งหน้า
        $ingredients = $query->paginate(10);

        // ดึงข้อมูลประเภทของวัตถุดิบสำหรับ chart
        $ingredientTypes = Ingredient::select('ingredient_type_id')
            ->selectRaw('count(*) as count')
            ->groupBy('ingredient_type_id')
            ->with('ingredientType') // เพิ่ม eager loading สำหรับ ingredient type
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->ingredientType->ingredient_type_name, // สมมติว่า ingredient type มี column 'ingredient_type_name'
                    'count' => $item->count
                ];
            });

        // ส่งข้อมูลไปยัง view
        return view('ingredients.index', compact('ingredients', 'ingredientTypes'));
    }

    public function create()
    {
        $ingredientTypes = IngredientType::all();
        return view('ingredients.create', compact('ingredientTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ingredient_name' => 'required|max:255',
            'ingredient_type_id' => 'required|exists:ingredient_types,id',
            'ingredient_unit' => 'required|max:50',
            'ingredient_stock' => 'required|min:0.00',
            'minimum_quantity' => 'required|min:0.00'
        ], [
            'ingredient_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
            'ingredient_name.max' => 'ชื่อวัตถุดิบไม่ควรเกิน 255 ตัวอักษร',
            'ingredient_type_id.required' => 'กรุณาเลือกประเภทวัตถุดิบ',
            'ingredient_unit.required' => 'กรุณากรอกหน่วยวัตถุดิบ',
            'ingredient_stock.required' => 'กรุณากรอกจำนวนวัตถุดิบ',
            'ingredient_stock.min' => 'จำนวนวัตถุดิบต้องไม่น้อยกว่า 0',
            'minimum_quantity.required' => 'กรุณากรอกจำนวนวัตถุดิบขั้นต่ำ',
            'minimum_quantity.min' => 'จำนวนวัตถุดิบขั้นต่ำต้องไม่น้อยกว่า 0'
        ]);

        // ตรวจสอบเฉพาะข้อมูลที่ยังไม่ถูกลบ
        $exists = Ingredient::where('ingredient_name', $request->ingredient_name)->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['ingredient_name' => 'พบวัตถุดิบนี้ในระบบแล้ว']);
        }

        // ตรวจสอบว่ามีข้อมูลที่ถูกลบแล้วหรือไม่
        $deletedIngredient = Ingredient::onlyTrashed()
            ->where('ingredient_name', $request->ingredient_name)
            ->first();

        if ($deletedIngredient) {
            // ถ้าพบข้อมูลที่ถูกลบแล้ว ให้เปิดใช้งานใหม่
            $deletedIngredient->restore();
            $deletedIngredient->update($validatedData);
            return redirect()->route('ingredients.index')->with('success', 'เปิดใช้งานและอัปเดตวัตถุดิบเรียบร้อยแล้ว');
        }

        // ถ้าไม่พบข้อมูลที่ซ้ำ ให้สร้างใหม่
        Ingredient::create($validatedData);

        return redirect()->route('ingredients.index')->with('success', 'เพิ่มวัตถุดิบใหม่เรียบร้อยแล้ว');
    }



    public function edit(Ingredient $ingredient)
    {
        $ingredientTypes = IngredientType::all();
        return view('ingredients.edit', compact('ingredient', 'ingredientTypes'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ingredient_name' => 'required|max:255',
            'ingredient_type_id' => 'required|exists:ingredient_types,id',
            'ingredient_unit' => 'required|max:50',
            'ingredient_stock' => 'required|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0'
        ], [
            'ingredient_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
            'ingredient_name.max' => 'ชื่อวัตถุดิบไม่ควรเกิน 255 ตัวอักษร',
            'ingredient_type_id.required' => 'กรุณาเลือกประเภทวัตถุดิบ',
            'ingredient_unit.required' => 'กรุณากรอกหน่วยวัตถุดิบ',
            'ingredient_stock.required' => 'กรุณากรอกจำนวนวัตถุดิบ',
            'ingredient_stock.numeric' => 'จำนวนวัตถุดิบต้องเป็นตัวเลข',
            'ingredient_stock.min' => 'จำนวนวัตถุดิบต้องไม่น้อยกว่า 0',
            'minimum_quantity.required' => 'กรุณากรอกจำนวนวัตถุดิบขั้นต่ำ',
            'minimum_quantity.numeric' => 'จำนวนวัตถุดิบขั้นต่ำต้องเป็นตัวเลข',
            'minimum_quantity.min' => 'จำนวนวัตถุดิบขั้นต่ำต้องไม่น้อยกว่า 0'

        ]);

        $ingredient = Ingredient::findOrFail($id);

        // ตรวจสอบว่ามีการเปลี่ยนชื่อวัตถุดิบหรือไม่
        if ($ingredient->ingredient_name !== $request->ingredient_name) {
            // ตรวจสอบว่ามีวัตถุดิบที่ใช้งานอยู่ที่มีชื่อเดียวกันหรือไม่
            $exists = Ingredient::where('ingredient_name', $request->ingredient_name)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return redirect()->back()->withErrors(['ingredient_name' => 'พบวัตถุดิบนี้ในระบบแล้ว']);
            }
            // ตรวจสอบว่ามีวัตถุดิบที่ถูกลบแล้วที่มีชื่อเดียวกันหรือไม่
            $deletedIngredient = Ingredient::onlyTrashed()
                ->where('ingredient_name', $request->ingredient_name)
                ->where('id', '!=', $id)
                ->first();
            if ($deletedIngredient) {
                // ถ้าพบข้อมูลที่ถูกลบแล้ว ให้แจ้งเตือนผู้ใช้
                return redirect()->back()->withErrors(['ingredient_name' => 'มีวัตถุดิบนี้ในระบบที่ถูกลบไปแล้ว กรุณาใช้ชื่ออื่น']);
            }
        }
        $ingredient->update($validatedData);
        return redirect()->route('ingredients.index')->with('success', 'อัปเดตวัตถุดิบเรียบร้อยแล้ว');
    }

    public function destroy(Ingredient $ingredient)
    {
        // ตรวจสอบว่าวัตถุดิบถูกใช้งานอยู่หรือไม่
        if ($ingredient->menuRecipes()->exists()) {
            return redirect()->route('ingredients.index')->with('error', 'ไม่สามารถลบวัตถุดิบได้ เนื่องจากยังมีการใช้งานอยู่ในสูตรอาหาร');
        }
    
        try {
            $ingredient->forceDelete(); // ลบถาวร
            return redirect()->route('ingredients.index')->with('success', 'ลบวัตถุดิบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // จัดการกับข้อผิดพลาดที่อาจเกิดขึ้นระหว่างการลบ
            return redirect()->route('ingredients.index')->with('error', 'เกิดข้อผิดพลาดในการลบวัตถุดิบ: ' . $e->getMessage());
        }
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric',
        ]);
        $ingredient = Ingredient::findOrFail($request->ingredient_id);
        $ingredient->ingredient_stock += $request->quantity;
        $ingredient->save();

        return response()->json(['success' => true, 'message' => 'อัปเดตจำนวนวัตถุดิบเรียบร้อยแล้ว', 'new_quantity' => $ingredient->ingredient_stock]);
    }
}
