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
        Schema::create('health_record_history', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('name')->required();
        $table->decimal('value', 8, 2)->nullable();
        $table->enum('visibility', ['public_all', 'public_connected', 'private'])->default('private');
        $table->string('record_file', 255)->nullable();
        $table->enum('record_type', ['file', 'text', 'report']);
        $table->text('record_details');
        $table->timestamp('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_record_history');
    }
};
