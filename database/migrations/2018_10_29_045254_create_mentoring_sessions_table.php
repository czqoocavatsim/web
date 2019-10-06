<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentoringSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentoring_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('time');
            $table->text('position');
            $table->longText('notes');
            $table->unsignedInteger('student');
            $table->foreign('student')->references('id')->on('users');
            $table->unsignedInteger('instructor');
            $table->foreign('instructor')->references('id')->on('users');
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
        Schema::dropIfExists('mentoring_sessions');
    }
}
