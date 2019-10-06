<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOTSSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_t_s_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('time');
            $table->text('position');
            $table->longText('notes');
            $table->unsignedInteger('student');
            $table->foreign('student')->references('id')->on('users');
            $table->unsignedInteger('instructor');
            $table->foreign('instructor')->references('id')->on('users');
            $table->unsignedInteger('adjudicator');
            $table->foreign('adjudicator')->references('id')->on('users');
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
        Schema::dropIfExists('o_t_s_sessions');
    }
}
