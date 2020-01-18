<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roster_member_id')->unsigned()->nullable();
            $table->foreign('roster_member_id')->references('id')->on('roster');
            $table->integer('cid');
            $table->dateTime('session_start');
            $table->dateTime('session_end')->nullable();
            $table->string('callsign');
            $table->float('duration')->nullable();
            $table->integer('emails_sent');
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
        Schema::dropIfExists('session_logs');
    }
}
