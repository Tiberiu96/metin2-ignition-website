<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const LOCALES = ['en', 'de', 'hu', 'fr', 'cs', 'da', 'es', 'el', 'it', 'nl', 'pl', 'pt', 'ro', 'ru', 'tr'];

    public function up(): void
    {
        // Drop unique index if it exists
        $indexExists = collect(DB::select('SHOW INDEX FROM news WHERE Key_name = "news_slug_unique"'))->isNotEmpty();
        if ($indexExists) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropUnique('news_slug_unique');
            });
        }

        // Change to TEXT first (accepts any string length without JSON validation)
        Schema::table('news', function (Blueprint $table) {
            $table->text('slug')->nullable()->change();
        });

        // Convert existing slug strings to JSON with locale suffixes
        DB::table('news')->orderBy('id')->each(function ($row) {
            $rawSlug = $row->slug;
            $decoded = json_decode($rawSlug, true);
            $baseSlug = is_array($decoded) ? ($decoded['en'] ?? Str::uuid()->toString()) : ($rawSlug ?: Str::uuid()->toString());
            $baseSlug = preg_replace('/-[a-z]{2}$/', '', $baseSlug);

            $slugs = [];
            foreach (self::LOCALES as $locale) {
                $slugs[$locale] = $baseSlug.'-'.$locale;
            }
            DB::table('news')->where('id', $row->id)->update([
                'slug' => json_encode($slugs),
            ]);
        });

        // Now change to JSON
        Schema::table('news', function (Blueprint $table) {
            $table->json('slug')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Change to TEXT first to allow non-JSON values
        Schema::table('news', function (Blueprint $table) {
            $table->text('slug')->nullable()->change();
        });

        DB::table('news')->orderBy('id')->each(function ($row) {
            $decoded = json_decode($row->slug, true);
            $enSlug = is_array($decoded) ? ($decoded['en'] ?? '') : $row->slug;
            $enSlug = preg_replace('/-en$/', '', $enSlug);
            DB::table('news')->where('id', $row->id)->update([
                'slug' => $enSlug,
            ]);
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->change();
            $table->unique('slug');
        });
    }
};
