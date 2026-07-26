<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Null means a single-day job: `preferred_date` is then both ends.
            $table->date('end_date')->nullable()->after('preferred_date');
            $table->index(['preferred_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['preferred_date', 'end_date']);
            $table->dropColumn('end_date');
        });
    }
};
