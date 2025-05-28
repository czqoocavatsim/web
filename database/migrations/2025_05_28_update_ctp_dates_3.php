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
        Schema::table('ctp_dates', function (Blueprint $table) {
            $table->string('name');
            $table->string('app')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctp_dates', function (Blueprint $table) {
            $table->dropColumn(['name']);
            $table->dropColumn(['app_link']);
        });
    }
};
