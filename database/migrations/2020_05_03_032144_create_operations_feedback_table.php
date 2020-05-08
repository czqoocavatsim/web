<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations_feedback', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Status
            $table->softDeletes();

            //Information
            $table->text('subject');
            $table->longText('content');

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
        Schema::dropIfExists('operations_feedback');
    }
}
