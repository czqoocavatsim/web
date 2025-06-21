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
        Schema::create('flights_log', function (Blueprint $table) {
            $table->id();
            $table->integer('cid');
            $table->string('callsign');
            $table->string('airline');
            $table->string('dep');
            $table->string('arr');
            $table->string('aircraft');
            $table->string('direction');
            $table->integer('still_inside')->nullable();
            $table->integer('save_details')->nullable();
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
