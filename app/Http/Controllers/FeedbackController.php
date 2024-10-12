<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Menu;

class FeedbackController extends Controller
{
    // ฟังก์ชันสำหรับแสดงฟอร์มการส่ง Feedback
    public function create()
    {
        $menus = Menu::all(); // ดึงเมนูทั้งหมดจากฐานข้อมูลเพื่อแสดงในฟอร์ม
        return view('feedbacks.create', compact('menus'));
    }

    // ฟังก์ชันสำหรับบันทึก Feedback ลงฐานข้อมูล
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        $request->validate([
            'menu_id' => 'required|exists:menus,id', // ตรวจสอบว่า menu_id มีอยู่ในตาราง menus
            'rating' => 'required|integer|min:1|max:5', // ตรวจสอบว่าคะแนนเป็นตัวเลขระหว่าง 1 ถึง 5
            'comment' => 'nullable|string', // คำติชมเป็น string และสามารถว่างได้
        ]);

        // สร้าง Feedback ใหม่และบันทึกลงฐานข้อมูล
        Feedback::create([
            'menu_id' => $request->menu_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ส่งกลับไปยังหน้าเดิมพร้อมข้อความแจ้งเตือน
        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    // ฟังก์ชันสำหรับแสดงผล Feedback ใน Dashboard ของเจ้าของร้าน
    public function index()
    {
        // ดึงข้อมูล Feedback ทั้งหมดพร้อมข้อมูลเมนูที่เกี่ยวข้อง
        $feedbacks = Feedback::with('menu')->get();
        return view('home', compact('feedbacks'));
    }
}
