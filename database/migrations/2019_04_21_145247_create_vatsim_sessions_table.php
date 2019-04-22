<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVatsimSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vatsim_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('controller')->unsigned()->nullable();
            $table->integer('vatsim_cid');
            $table->integer('position')->unsigned();
            $table->dateTime('session_start');
            $table->dateTime('session_end');
            $table->integer('status')->default(0);
            $table->foreign('controller')->references('id')->on('roster');
            $table->foreign('position')->references('id')->on('vatsim_positions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vatsim_sessions');
    }
}
