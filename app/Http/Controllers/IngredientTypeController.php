<?php

namespace App\Http\Controllers;

use App\Models\IngredientType;
use Illuminate\Http\Request;

class IngredientTypeController extends Controller
{
    public function index()
    {
        $ingredientTypes = IngredientType::all();
        return view('ingredients.ingredient_types.index', compact('ingredientTypes'));
    }

    public function create()
    {
        return view('ingredients.ingredient_types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'ingredient_type_name' => 'required|max:255',
                'ingredient_type_detail' => 'nullable',
            ],
            [
                'ingredient_type_name.required' => 'กรุณากรอกชื่อประเภทวัตถุดิบ',
                'ingredient_type_name.max' => 'ชื่อประเภทวัตถุดิบไม่ควรเกิน 255 ตัวอักษร',
            ]
        );
        // ตรวจสอบเฉพาะข้อมูลที่ยังไม่ถูกลบ
        $exists = IngredientType::where('ingredient_type_name', $request->ingredient_type_name)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['ingredient_type_name' => 'พบประเภทวัตถุดิบนี้ในระบบแล้ว']);
        }
        // ตรวจสอบว่ามีข้อมูลที่ถูกลบแล้วหรือไม่
        $deletedIngredient = IngredientType::onlyTrashed()
            ->where('ingredient_type_name', $request->ingredient_type_name)
            ->first();
        if ($deletedIngredient) {
            // ถ้าพบข้อมูลที่ถูกลบแล้ว ให้เปิดใช้งานใหม่
            $deletedIngredient->restore();
            $deletedIngredient->update($validatedData);
            return redirect()->route('ingredients.index')->with('success', 'เปิดใช้งานและอัปเดตวัตถุดิบเรียบร้อยแล้ว');
        }
        // ถ้าไม่พบข้อมูลที่ซ้ำ ให้สร้างใหม่
        IngredientType::create($validatedData);
        return redirect()->route('ingredient_types.index')
            ->with('success', 'ประเภทวัตถุดิบถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function show(IngredientType $ingredientType)
    {
        return view('ingredients.ingredient_types.show', compact('ingredientType'));
    }

    public function edit(IngredientType $ingredientType)
    {
        return view('ingredients.ingredient_types.edit', compact('ingredientType'));
    }

    public function update(Request $request, IngredientType $ingredientType)
    {
        $request->validate([
            'ingredient_type_name' => 'required|max:255',
            'ingredient_type_detail' => 'nullable',
        ]);

        $ingredientType->update($request->all());

        return redirect()->route('ingredient_types.index')
            ->with('success', 'ประเภทวัตถุดิบถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(IngredientType $ingredientType)
    {

        // ลบข้อมูลใน ingredients ที่เกี่ยวข้อง
        $ingredientType->ingredients()->delete();

        // ลบข้อมูลใน ingredient_types
        $ingredientType->delete();

        return redirect()->route('ingredient_types.index')
            ->with('success', 'ประเภทวัตถุดิบและวัตถุดิบประเภทนี้ ถูกลบออกเรียบร้อยแล้ว');
    }
}
