<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRotationImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotation_images', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Information
            $table->string('path');

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
        Schema::dropIfExists('rotation_images');
    }
}
