<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name'); // ชื่อเมนู
            $table->string('menu_detail')->nullable(); // รายละเอียดเมนู
            $table->foreignId('menu_type_id')->constrained('menu_types')->onDelete('cascade');
            $table->double('menu_price', 10, 2);
            $table->boolean('menu_status')->default(true); // สถานะเมนู (true = available, false = not available)
            $table->string('menu_image')->nullable(); // ภาพเมนู

            $table->softDeletes(); // ลบเมนูอย่างอ่อน (บันทึกเวลาเมนูถูกลบ)
            $table->timestamps();

            $table->index('menu_name'); // เพิ่มดัชนีบนชื่อเมนู
            $table->index('menu_type_id'); // เพิ่มดัชนีบนประเภทเมนู
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
