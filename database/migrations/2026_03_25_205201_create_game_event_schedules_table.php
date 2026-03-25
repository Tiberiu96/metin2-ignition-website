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
        Schema::create('game_event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_event_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('stop_at')->nullable();
            $table->boolean('started')->default(false);
            $table->boolean('stopped')->default(false);
            $table->string('repeat_type', 20)->default('none');
            $table->json('params_override')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_event_schedules');
    }
};
