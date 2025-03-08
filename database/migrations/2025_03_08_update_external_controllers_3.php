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
        Schema::table('external_controllers', function (Blueprint $table) {
            $table->float('currency')->nullable();
            $table->float('monthly_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_controllers', function (Blueprint $table) {
            $table->dropColumn(['currency']);
            $table->dropColumn(['monthly_hours']);
        });
    }
};
