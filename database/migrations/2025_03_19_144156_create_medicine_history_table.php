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
        Schema::create('medicine_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medicine_report_id')->constrained('medicine_reports')->onDelete('cascade');
            $table->string('previous_dosage')->nullable();
            $table->enum('previous_frequency', ['once', 'twice', 'thrice', 'daily'])->nullable();
            $table->date('previous_start_date')->nullable();
            $table->date('previous_end_date')->nullable();
            $table->timestamps(0); // Creates 'created_at' and 'updated_at' columns
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_history');
    }
};
