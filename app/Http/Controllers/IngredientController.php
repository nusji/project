<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredient::with('ingredientType');

        // Check if there are any search parameters
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ingredient_name', 'like', "%{$search}%")
                ->orWhereHas('ingredientType', function ($q) use ($search) { //เป็
                    $q->where('ingredient_name', 'like', "%{$search}%");
                });
        }

        $ingredients = $query->paginate(10);

        return view('ingredients.index', compact('ingredients'));
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
            'ingredient_quantity' => 'required|numeric|min:0',
        ], [
            'ingredient_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
            'ingredient_name.max' => 'ชื่อวัตถุดิบไม่ควรเกิน 255 ตัวอักษร',
            'ingredient_type_id.required' => 'กรุณาเลือกประเภทวัตถุดิบ',
            'ingredient_unit.required' => 'กรุณากรอกหน่วยวัตถุดิบ',
            'ingredient_quantity.required' => 'กรุณากรอกจำนวนวัตถุดิบ',
            'ingredient_quantity.numeric' => 'จำนวนวัตถุดิบต้องเป็นตัวเลข',
            'ingredient_quantity.min' => 'จำนวนวัตถุดิบต้องไม่น้อยกว่า 0',
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
        'ingredient_quantity' => 'required|numeric|min:0',
    ], [
        'ingredient_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
        'ingredient_name.max' => 'ชื่อวัตถุดิบไม่ควรเกิน 255 ตัวอักษร',
        'ingredient_type_id.required' => 'กรุณาเลือกประเภทวัตถุดิบ',
        'ingredient_unit.required' => 'กรุณากรอกหน่วยวัตถุดิบ',
        'ingredient_quantity.required' => 'กรุณากรอกจำนวนวัตถุดิบ',
        'ingredient_quantity.numeric' => 'จำนวนวัตถุดิบต้องเป็นตัวเลข',
        'ingredient_quantity.min' => 'จำนวนวัตถุดิบต้องไม่น้อยกว่า 0',
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
        $ingredient->delete(); // ทำ Soft Delete

        return redirect()->route('ingredients.index')->with('success', 'ลบวัตถุดิบเรียบร้อยแล้ว');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);
        $ingredient->ingredient_quantity += $request->quantity;
        $ingredient->save();

        return response()->json(['success' => true, 'message' => 'อัปเดตจำนวนวัตถุดิบเรียบร้อยแล้ว', 'new_quantity' => $ingredient->ingredient_quantity]);
    }
}
