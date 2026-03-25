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
        Schema::create('game_event_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_event_id')->constrained()->cascadeOnDelete();
            $table->string('action', 30);
            $table->json('params_snapshot')->nullable();
            $table->string('triggered_by', 20);
            $table->foreignId('user_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_event_logs');
    }
};
