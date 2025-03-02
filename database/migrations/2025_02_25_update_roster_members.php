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
        Schema::table('roster_members', function (Blueprint $table) {
            $table->integer('certified_in_q3')->nullable();
            $table->integer('certified_in_q4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roster_members', function (Blueprint $table) {
            $table->dropColumn(['certified_in_q3']);
            $table->dropColumn(['certified_in_q4']);
        });
    }
};
