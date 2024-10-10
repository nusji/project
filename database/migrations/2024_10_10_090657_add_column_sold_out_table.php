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
        Schema::table('production_details', function (Blueprint $table) {
            $table->boolean('is_sold_out')->default(false); // เพิ่มคอลัมน์ is_sold_out ใน production details
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_details', function (Blueprint $table) {
            $table->dropColumn('is_sold_out');
        });
    }
};
