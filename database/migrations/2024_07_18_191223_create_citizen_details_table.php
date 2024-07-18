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
        Schema::create('citizen_details', function (Blueprint $table) {
            $table->id('citizen_id');
            $table->text('firstname');
            $table->text('middle_name')->nullable();
            $table->text('lastname');
            $table->text('suffix')->nullable();
            $table->text('address');
            $table->date('date_of_birth')->nullable();
            $table->text('gender');
            $table->text('citizen_status');
            $table->text('blood_type')->nullable();
            $table->text('height');
            $table->text('weight');
            $table->text('allergies')->nullable();
            $table->text('condition');
            $table->text('medication');
            $table->text('emergency_contact_name');
            $table->text('emergency_contact_no');
            $table->text('services_availed');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen_details');
    }
};
