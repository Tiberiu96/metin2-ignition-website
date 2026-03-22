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
        Schema::table('coin_packages', function (Blueprint $table) {
            $table->decimal('price_eur_original', 8, 2)->nullable()->after('price_eur');
        });
    }

    public function down(): void
    {
        Schema::table('coin_packages', function (Blueprint $table) {
            $table->dropColumn('price_eur_original');
        });
    }
};
