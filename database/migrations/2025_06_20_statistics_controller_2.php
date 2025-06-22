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
        Schema::create('statistics_controller', function (Blueprint $table) {
            $table->id();
            $table->integer('cid');
            $table->string('current')->nullable();
            $table->string('last_month')->nullable();
            $table->string('year')->nullable();
            $table->string('visiting_origin')->nullable();
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
