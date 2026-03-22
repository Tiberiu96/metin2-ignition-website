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
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id')->index();
            $table->string('type');
            $table->unsignedInteger('coins');
            $table->decimal('amount_eur', 8, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('coupon_code')->nullable()->index();
            $table->string('stripe_session_id')->nullable()->index();
            $table->string('status');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
