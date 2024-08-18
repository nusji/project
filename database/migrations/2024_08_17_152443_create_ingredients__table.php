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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); // เปลี่ยนชื่อคอลัมน์ให้ชัดเจน
            $table->string('ingredient_name');
            $table->string('ingredient_detail')->nullable();
            $table->string('ingredient_unit');
            $table->double('ingredient_quantity', 10, 2)->default(0.00);
            // คอลัมน์นี้ใช้สำหรับอ้างอิงไปยังตาราง ingredients_type
            $table->foreignId('ingredient_type_id')->constrained('ingredients_type')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
