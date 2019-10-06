<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->increments('id');
            $table->string('application_id')->default(Str::random(8));
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('status')->default(0); /*0 = pending, 1 = denied, 2 = accepted, 3 = withdrawn*/
            $table->dateTime('submitted_at');
            $table->dateTime('processed_at')->nullable();
            $table->unsignedInteger('processed_by')->nullable();
            $table->foreign('processed_by')->references('id')->on('users')->nullable();
            $table->text('applicant_statement')->nullable();
            $table->text('staff_comment')->nullable();
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
