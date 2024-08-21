<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Menu;
use Illuminate\Http\Request;

class ProductionController extends Controller
{

    public function index()
    {
        $productions = Production::all();
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('productions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $production = Production::create([
            'production_date' => $request->input('production_date'),
            'comment' => $request->input('comment'),
        ]);

        foreach ($request->input('menu_id', []) as $key => $menuId) {
            $quantity = $request->input('quantity.' . $key);
            $productionDetail = new ProductionDetail([
                'menu_id' => $menuId,
                'quantity' => $quantity,
            ]);
            $production->productionDetails()->save($productionDetail);
        }

        return redirect()->route('productions.show', $production);
    }

    public function show(Production $production)
    {
        $production->load('productionDetails.menu');
        return view('productions.show', compact('production'));
    }

    public function edit(Production $production)
    {
        $menus = Menu::all();
        $production->load('productionDetails');
        return view('productions.edit', compact('production', 'menus'));
    }

    public function update(Request $request, Production $production)
    {
        $production->update([
            'production_date' => $request->input('production_date'),
            'comment' => $request->input('comment'),
        ]);

        $production->productionDetails()->delete();

        foreach ($request->input('menu_id', []) as $key => $menuId) {
            $quantity = $request->input('quantity.' . $key);
            $productionDetail = new ProductionDetail([
                'menu_id' => $menuId,
                'quantity' => $quantity,
            ]);
            $production->productionDetails()->save($productionDetail);
        }

        return redirect()->route('productions.show', $production);
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return redirect()->route('productions.index');
    }
}