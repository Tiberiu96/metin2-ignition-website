<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wrap existing name values in JSON {"en": "..."} before column type change
        DB::table('shop_categories')->orderBy('id')->whereNotNull('name')->each(function ($row) {
            DB::table('shop_categories')->where('id', $row->id)->update([
                'name' => json_encode(['en' => $row->name]),
            ]);
        });

        Schema::table('shop_categories', function (Blueprint $table) {
            $table->json('name')->change();
        });

        DB::table('shop_items')->orderBy('id')->whereNotNull('name')->each(function ($row) {
            $updates = ['name' => json_encode(['en' => $row->name])];
            if ($row->description) {
                $updates['description'] = json_encode(['en' => $row->description]);
            }
            DB::table('shop_items')->where('id', $row->id)->update($updates);
        });

        Schema::table('shop_items', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Extract English values back from JSON
        DB::table('shop_categories')->orderBy('id')->each(function ($row) {
            $decoded = json_decode($row->name, true);
            DB::table('shop_categories')->where('id', $row->id)->update([
                'name' => is_array($decoded) ? ($decoded['en'] ?? '') : $row->name,
            ]);
        });

        Schema::table('shop_categories', function (Blueprint $table) {
            $table->string('name')->change();
        });

        DB::table('shop_items')->orderBy('id')->each(function ($row) {
            $decodedName = json_decode($row->name, true);
            $decodedDesc = $row->description ? json_decode($row->description, true) : null;
            DB::table('shop_items')->where('id', $row->id)->update([
                'name' => is_array($decodedName) ? ($decodedName['en'] ?? '') : $row->name,
                'description' => is_array($decodedDesc) ? ($decodedDesc['en'] ?? null) : $row->description,
            ]);
        });

        Schema::table('shop_items', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->nullable()->change();
        });
    }
};
