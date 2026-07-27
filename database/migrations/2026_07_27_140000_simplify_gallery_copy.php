<?php

use Database\Seeders\GallerySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

/**
 * Re-run the gallery seeder so the simplified, cleaning-only descriptions
 * (no "remise en état" / "remise à blanc", which auto-translate to
 * "restoration") reach production. The seeder now uses updateOrCreate, so
 * existing rows are updated in place - real admin-uploaded galleries keep
 * their own titles and are untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        Artisan::call('db:seed', ['--class' => GallerySeeder::class, '--force' => true]);
    }

    public function down(): void
    {
        // No rollback.
    }
};
