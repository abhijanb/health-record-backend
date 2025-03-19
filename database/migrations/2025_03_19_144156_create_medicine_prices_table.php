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
        Schema::create('medicine_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_report_id')->constrained('medicine_reports')->onDelete('cascade');
            $table->string('store_name');
            $table->decimal('price', 10, 2);
            $table->string('store_url')->nullable();
            $table->timestamps(0); // Creates 'created_at' and 'updated_at' columns
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_prices');
    }
};
