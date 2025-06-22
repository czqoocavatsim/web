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
        Schema::create('statistics_airports', function (Blueprint $table) {
            $table->id();
            $table->string('airport');
            $table->string('current_dep')->nullable();
            $table->string('current_arr')->nullable();
            $table->string('last_month_dep')->nullable();
            $table->string('last_month_arr')->nullable();
            $table->string('year_dep')->nullable();
            $table->string('year_arr')->nullable();
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
        //
    }
};
