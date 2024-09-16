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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');  // วันที่ผลิต
            $table->string('production_detail');  // รายละเอียดการผลิต
            $table->timestamps();  // created_at, updated_at
            $table->softDeletes();  // สำหรับการลบแบบ soft delete
        });

        Schema::create('production_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('productions')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->decimal('produced_quantity', 10, 2);  // ปริมาณที่ผลิต (เช่น กิโลกรัม)
            $table->decimal('selling_quantity', 10, 2);   // จำนวนขาย (เช่น ทัพพี)
            $table->timestamps();
        });

        Schema::create('production_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_menu_id')->constrained('production_menus')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('used_quantity', 10, 2);  // ปริมาณวัตถุดิบที่ใช้ในการผลิต
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
        Schema::dropIfExists('production_menus');
        Schema::dropIfExists('production_ingredients');
    }
};
