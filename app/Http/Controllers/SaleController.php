<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Menu;
use Illuminate\Http\Request;
use DB;


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('employee', 'saleDetails.menu')
                     ->orderBy('sale_date', 'desc')
                     ->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('sales.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $sale = Sale::create([
            'sale_date' => now(),
            'employee_id' => auth()->user()->id, // Assuming you're using authentication
            'payment_type' => $request->payment_type,
        ]);

        foreach ($request->items as $item) {
            SaleDetail::create([
                'sale_id' => $sale->id,
                'menu_id' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['success' => true, 'sale_id' => $sale->id]);
    }

    public function show(Sale $sale)
    {
        $sale->load('employee', 'saleDetails.menu');
        return view('pos.show', compact('sale'));
    }
}