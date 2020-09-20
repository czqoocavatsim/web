<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoloCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solo_certifications', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Assoicated controller
            $table->unsignedInteger('roster_member_id');
            $table->foreign('roster_member_id')->references('id')->on('roster_members');

            //Dates
            $table->dateTime('expires')->nullable();

           //Approved by
           $table->unsignedInteger('instructor_id');
           $table->foreign('instructor_id')->references('id')->on('users');


            //Remarks
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('solo_certifications');
    }
}
