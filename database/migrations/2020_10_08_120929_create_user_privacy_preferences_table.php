<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrivacyPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_privacy_preferences', function (Blueprint $table) {
            $table->id();

            //User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Options
            $table->boolean('avatar_public')->default(true);
            $table->boolean('biography_public')->default(true);
            $table->boolean('session_logs_public')->default(true);
            $table->boolean('certification_details_public')->default(true);

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
        Schema::dropIfExists('user_privacy_preferences');
    }
}
