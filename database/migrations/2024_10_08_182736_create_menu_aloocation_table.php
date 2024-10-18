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
        Schema::create('menu_allocations', function (Blueprint $table) {
            $table->id();
            $table->date('allocation_date');
            $table->timestamps();
        });

        Schema::create('menu_allocation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_allocation_id')->constrained('menu_allocations')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_allocations');
        Schema::dropIfExists('menu_allocation_details');
    }
};
