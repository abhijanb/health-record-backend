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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('name')->required();
        $table->enum('record_type', ['file', 'text', 'report']);
        $table->text('record_details');
        $table->string('record_file', 255)->nullable();
        $table->date('date_recorded');
        $table->enum('visibility', ['public_all', 'public_connected', 'private'])->default('private');
        $table->decimal('value');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
