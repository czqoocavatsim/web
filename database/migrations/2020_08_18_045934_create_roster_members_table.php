<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRosterMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_members', function (Blueprint $table) {
            //Identification
            $table->increments('id');
            $table->integer('cid');

            //Assoicated user
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Certification
            $table->enum('certification', ['certified', 'training', 'not_certified'])->default('not_certified');
            $table->dateTime('date_certified')->nullable();

            //Activity
            $table->boolean('active')->default(false);
            $table->float('monthly_hours')->nullable();

            //Remarks
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('roster_members');
    }
}
