<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('citizen_history', function (Blueprint $table) {
            $table->string('firstname');
            $table->string('middle_name')->nullable();
            $table->string('lastname');
            $table->string('address');
            $table->date('date_of_birth')->nullable();
            $table->string('gender');
            $table->string('citizen_status');
            $table->string('blood_type')->nullable();
            $table->string('height');
            $table->string('weight');
            $table->string('allergies')->nullable();
            $table->string('condition');
            $table->string('medication')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citizen_history', function (Blueprint $table) {
            $table->dropColumn([
                'firstname',
                'middle_name',
                'lastname',
                'address',
                'date_of_birth',
                'gender',
                'citizen_status',
                'blood_type',
                'height',
                'weight',
                'allergies',
                'condition',
                'medication',
                'emergency_contact_name',
                'emergency_contact_no',
            ]);
        });
    }
};
