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
        Schema::create('statistics_airport_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('airport1');
            $table->string('airport2');
            $table->float('current')->nullable();
            $table->float('last_month')->nullable();
            $table->float('year')->nullable();
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
