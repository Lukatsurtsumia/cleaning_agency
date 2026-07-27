<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

/**
 * Seeds the launch content (admin login + demo gallery cards) as part of
 * `migrate`, so a Coolify deploy with AUTORUN_LARAVEL_MIGRATION=true comes up
 * fully populated with no separate `db:seed` step.
 *
 * DatabaseSeeder is idempotent (firstOrCreate on the admin email and each
 * gallery title), so this is safe to have run on every deploy. Skipped under
 * the test suite, which manages its own data via RefreshDatabase + factories.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        Artisan::call('db:seed', ['--force' => true]);
    }

    public function down(): void
    {
        // Seed data is left in place on rollback.
    }
};
