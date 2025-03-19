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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->enum('notification_type', ['record_shared', 'record_updated', 'new_connection']);
        $table->text('message');
        $table->boolean('is_read')->default(false);
        $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
