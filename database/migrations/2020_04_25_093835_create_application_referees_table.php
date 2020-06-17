<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationRefereesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_referees', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Associated application
            $table->unsignedInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');

            //Status
            $table->softDeletes();

            //Data
            $table->string('referee_full_name');
            $table->string('referee_email')->nullable();
            $table->string('referee_staff_position')->nullable();

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
        Schema::dropIfExists('application_referees');
    }
}
