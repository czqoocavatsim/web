<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();

            //User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Training
            $table->enum('training_notifications', ['email', 'email+discord'])->default('email');

            //Events
            $table->enum('event_notifications', ['off', 'email'])->default('off');

            //News
            $table->enum('news_notifications', ['off', 'email'])->default('off');

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
        Schema::dropIfExists('user_notification_preferences');
    }
}
