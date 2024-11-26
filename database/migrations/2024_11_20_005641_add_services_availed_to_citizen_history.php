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
        Schema::table('citizen_history', function (Blueprint $table) {
            // Add a json column for services_availed
            $table->json('services_availed')->nullable();

            // Add a visit_date column for the date of visit
            $table->date('visit_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_history', function (Blueprint $table) {
            // Drop the columns when rolling back the migration
            $table->dropColumn(['services_availed', 'visit_date']);
        });
    }
};
