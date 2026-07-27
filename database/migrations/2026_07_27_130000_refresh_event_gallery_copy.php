<?php

use Database\Seeders\GallerySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * The gallery reseed only runs once and firstOrCreate never overwrites an
 * existing row, so an earlier copy fix (drop "preparation"/reception wording
 * from the event project) never reached production. Clear the placeholder rows
 * again - any gallery with no uploaded cover image - and reseed with the
 * current, corrected GallerySeeder. Real admin-uploaded galleries (which have a
 * cover_image) are left untouched.
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
