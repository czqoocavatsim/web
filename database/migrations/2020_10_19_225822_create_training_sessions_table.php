<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            //ID
            $table->id();

            //People involved
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('instructor_id'); //Instructor running session
            $table->foreign('instructor_id')->references('id')->on('instructors');

            //Time scheduled
            $table->dateTime('scheduled_time');

            //Remarks
            $table->longText('remarks')->nullable();

            //Position
            $table->unsignedInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('monitored_positions');

            //Timestamps and soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_sessions');
    }
}
