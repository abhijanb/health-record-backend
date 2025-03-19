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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('diagnosis', 255);
            $table->date('diagnosis_date')->nullable();
            $table->string('treatment', 255)->nullable();
            $table->string('doctor_name', 255)->nullable();
            $table->string('hospital_name', 255)->nullable();
            $table->boolean('surgery')->default(false);
            $table->boolean('vaccine')->default(false);
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
