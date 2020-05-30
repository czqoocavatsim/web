<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionIdToSessionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_logs', function (Blueprint $table) {
            $table->dropColumn('callsign');
            $table->integer('monitored_position_id')->unsigned()->nullable();
            $table->foreign('monitored_position_id')->references('id')->on('monitored_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_logs', function (Blueprint $table) {
            //
        });
    }
}
