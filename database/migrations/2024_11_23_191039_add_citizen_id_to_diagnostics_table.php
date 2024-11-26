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
        Schema::table('diagnostic', function (Blueprint $table) {
        // Ensure citizen_id column exists before applying the foreign key
        $table->unsignedBigInteger('citizen_id')->nullable(); // Or remove nullable if you want it to be required
        
        // Add the foreign key constraint
        $table->foreign('citizen_id')
              ->references('citizen_id')
              ->on('citizen_details')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('diagnostic', function (Blueprint $table) {
        // Drop the foreign key and the citizen_id column if necessary
        $table->dropForeign(['citizen_id']);
        $table->dropColumn('citizen_id');
    });
    }
};
