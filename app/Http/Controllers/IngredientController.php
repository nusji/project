<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientType;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate(
            [
                'ingredient_name' => 'required|max:255|unique:ingredients',
                'ingredient_type_id' => 'required|exists:ingredients_type,id',
                'ingredient_unit' => 'required|max:50',
                'ingredient_quantity' => 'required|numeric|min:0',
            ],
            [
                'ingredient_name.unique' => 'พบวัตถุดิบนี้ในระบบแล้ว',
                'ingredient_type_id.require' => 'กรุณาเลือกประเภทวัตถุดิบ',
                'ingredient_unit.require' => 'กรุณากรอกหน่วยวัตถุดิบ',
                'ingredient_quantity.require' => 'กรุณากรอกจำนวนวัตถุดิบ',
            ]
        );

        Ingredient::create($validatedData);

        return redirect()->route('ingredients.index')->with('success', 'เพิ่มวัตถุดิบใหม่เรียบร้อยแล้ว');
    }

    public function edit(Ingredient $ingredient)
    {
        $ingredientTypes = IngredientType::all();
        return view('ingredients.edit', compact('ingredient', 'ingredientTypes'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validatedData = $request->validate(
            [
                'ingredient_name' => 'required|max:255|unique:ingredients,ingredient_name,' . $ingredient->id,
                'ingredient_type_id' => 'required|exists:ingredients_type,id',
                'ingredient_unit' => 'required|max:50',
                'ingredient_quantity' => 'required|numeric|min:0',
            ],
            [
                'ingredient_name.unique' => 'พบวัตถุดิบนี้ในระบบแล้ว',
                'ingredient_type_id.require' => 'กรุณาเลือกประเภทวัตถุดิบ',
                'ingredient_unit.require' => 'กรุณากรอกหน่วยวัตถุดิบ',
                'ingredient_quantity.require' => 'กรุณากรอกจำนวนวัตถุดิบ',
            ]
        );

        $ingredient->update($validatedData);

        return redirect()->route('ingredients.index')->with('success', 'อัปเดตวัตถุดิบเรียบร้อยแล้ว');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

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
