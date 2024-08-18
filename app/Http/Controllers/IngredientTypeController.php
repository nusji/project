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
        $request->validate([
            'ingredient_type_name' => 'required|max:255',
            'ingredient_type_detail' => 'nullable',
        ]);

        IngredientType::create($request->all());

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

        return redirect()->route('ingredients.ingredient_types.index')
            ->with('success', 'ประเภทวัตถุดิบถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(IngredientType $ingredientType)
    {
        $ingredientType->delete();

        return redirect()->route('ingredient_types.index')
            ->with('success', 'ประเภทวัตถุดิบและวัตถุดิบประเภทนี้ ถูกลบออกเรียบร้อยแล้ว');
    }
}