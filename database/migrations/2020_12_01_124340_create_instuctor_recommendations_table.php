<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstuctorRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instuctor_recommendations', function (Blueprint $table) {
            $table->id();
            //Student ID
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            //Instrutor ID (for the person who created it)
            $table->unsignedBigInteger('instructor_id');
            $table->foreign('instructor_id')->references('id')->on('instructors');

            $table->string('type');

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
        Schema::dropIfExists('instuctor_recommendations');
    }
}
