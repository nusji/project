<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;


class FeedbackController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ดึงรายการ menu_id จาก production_details ที่มี production_date ตรงกับวันนี้
        $productionMenuIds = ProductionDetail::whereHas('production', function ($query) use ($today) {
            $query->whereDate('production_date', $today);
        })->pluck('menu_id');

        $feedbacks = Feedback::with('menu')
            ->whereIn('menu_id', $productionMenuIds)
            ->get();

        $groupedFeedbacks = $feedbacks->groupBy('menu_id');

        $averageRatings = $groupedFeedbacks->map(function ($menuFeedbacks) {
            return [
                'menu' => $menuFeedbacks->first()->menu,
                'average_rating' => $menuFeedbacks->avg('rating'),
                'feedbacks' => $menuFeedbacks
            ];
        });

        return view('feedbacks.index', compact('averageRatings'));
    }



    public function create(Request $request)
    {
        $query = $request->input('query');
        $today = Carbon::today();
    
        // ปรับปรุงการค้นหาโดยใช้ whereHas กับความสัมพันธ์ 'production'
        $productionDetailsQuery = ProductionDetail::with('menu')
            ->whereHas('production', function ($q) use ($today) {
                $q->whereDate('production_date', $today);
            });
    
        if ($query) {
            $productionDetailsQuery->whereHas('menu', function ($q) use ($query) {
                $q->where('menu_name', 'like', '%' . $query . '%');
            });
        }
    
        $productionDetails = $productionDetailsQuery->get();
    
        if ($productionDetails->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่พบเมนูที่ค้นหา');
        }
    
        $menus = $productionDetails->pluck('menu')->unique('id');
    
        return view('feedbacks.create', compact('menus', 'query'));
    }
    



    public function review(Request $request)
    {
        $menu_id = $request->input('menu_id');
        $menu = Menu::findOrFail($menu_id);

        return view('feedbacks.review', compact('menu'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Feedback::create($request->only('menu_id', 'rating', 'comment'));

        return redirect()->route('feedbacks.index')->with('success', 'ขอบคุณสำหรับคำติชม!');
    }
}
