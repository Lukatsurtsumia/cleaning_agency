<?php

use Database\Seeders\GallerySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * The gallery was first seeded with placeholder demo projects that no longer
 * match the business (offices, medical office, student residence...). This
 * drops those placeholder rows - any gallery with no uploaded cover image -
 * and reseeds the refreshed project list (hotels, apartments, houses & villas,
 * events). Real admin-uploaded galleries have a cover_image and are untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        DB::table('galleries')->whereNull('cover_image')->delete();

        Artisan::call('db:seed', ['--class' => GallerySeeder::class, '--force' => true]);
    }

    public function down(): void
    {
        // No rollback: the refreshed gallery content stays in place.
    }
};
