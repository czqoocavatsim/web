<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            //Identification
            $table->increments('id');
            $table->string('reference_id', 6)->nullable();

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Status
            $table->integer('status')->default(0);
            $table->softDeletes();

            //Information
            $table->longText('applicant_statement')->nullable();

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
        Schema::dropIfExists('applications');
    }
}
