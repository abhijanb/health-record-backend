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
        Schema::create('medicine_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('medicine_name');
            $table->string('dosage')->nullable();
            $table->enum('frequency', ['once', 'twice', 'thrice', 'daily']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('prescription')->nullable();
            $table->timestamps(0); // Creates 'created_at' and 'updated_at' columns
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_reports');
    }
};
