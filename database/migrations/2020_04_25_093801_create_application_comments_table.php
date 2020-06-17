<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_comments', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Associated application
            $table->unsignedInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Status
            $table->softDeletes();

            //Information
            $table->longText('content')->nullable();

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
        Schema::dropIfExists('application_comments');
    }
}
