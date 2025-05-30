<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('militaryrating_id')->nullable();
            $table->string('militaryrating_short')->nullable();
            $table->string('militaryrating_long')->nullable();
            $table->integer('vatsim_gdpr_account')->nullable(); //VATSIM Account no longer exists
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['militaryrating_id']);
            $table->dropColumn(['militaryrating_short']);
            $table->dropColumn(['militaryrating_long']);
            $table->dropColumn(['vatsim_gdpr_account']);
        });
    }
};
