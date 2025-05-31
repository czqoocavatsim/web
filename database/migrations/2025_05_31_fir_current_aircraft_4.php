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
        Schema::create('fir_current_aircraft', function (Blueprint $table) {
            $table->id();
            $table->integer('cid');
            $table->string('callsign');
            $table->integer('still_inside')->nullable();
            $table->integer('point_recorded')->nullable();
            $table->datetime('exited_oca')->nullable();
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
