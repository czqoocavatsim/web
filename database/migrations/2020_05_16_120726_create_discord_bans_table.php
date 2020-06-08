<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_bans', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //User who gave the ban
            $table->unsignedInteger('moderator_id');
            $table->foreign('moderator_id')->references('id')->on('users');

            //Status
            $table->softDeletes();

            //Ban information
            $table->text('reason');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->bigInteger('discord_id')->nullable();

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
        Schema::dropIfExists('discord_bans');
    }
}
