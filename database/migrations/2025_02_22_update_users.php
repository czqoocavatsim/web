<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('pilotrating_id')->nullable();
            $table->string('pilotrating_short')->nullable();
            $table->string('pilotrating_long')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pilotrating_id']);
            $table->dropColumn(['pilotrating_short']);
            $table->dropColumn(['pilotrating_long']);
        });
    }
};
