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
        Schema::table('shanwick_controllers', function (Blueprint $table) {
            $table->string('region_code')->nullable();
            $table->string('region_name')->nullable();
            $table->string('division_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shanwick_controllers', function (Blueprint $table) {
            $table->dropColumn(['region_code', 'region_name', 'division_name']);
        });
    }
};
