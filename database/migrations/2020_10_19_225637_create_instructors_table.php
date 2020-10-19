<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructors', function (Blueprint $table) {
            //ID
            $table->id();

            //User assignment
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Currency
            $table->boolean('current')->default(true); //Will disable instructor functions if false
            $table->boolean('assessor')->default(false);

            //Staff email and page data
            $table->string('staff_email')->nullable(); //Will reference user email if null
            $table->string('staff_page_tagline')->nullable(); //Will automatically assign if null, use this for 'Assistant Chief Instructor' for example

            //Timestamps
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
        Schema::dropIfExists('instructors');
    }
}
