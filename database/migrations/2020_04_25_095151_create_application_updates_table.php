<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_updates', function (Blueprint $table) {
            //Identification
            $table->increments('id');

            //Associated application
            $table->unsignedInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications');

            //Status
            $table->softDeletes();

            //Information
            $table->string('update_title');
            $table->longText('update_content')->nullable();
            $table->string('update_type')->nullable();

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
        Schema::dropIfExists('application_updates');
    }
}
