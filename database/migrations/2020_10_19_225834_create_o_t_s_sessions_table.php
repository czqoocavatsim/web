<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOTSSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_t_s_sessions', function (Blueprint $table) {
            //ID
            $table->id();

            //People involved
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('assessor_id'); //Assessor running session
            $table->foreign('assessor_id')->references('id')->on('instructors');

            //Time scheduled
            $table->dateTime('scheduled_time');

            //Remarks and result
            $table->longText('remarks')->nullable();
            $table->enum('result', ['passed', 'failed', 'pending'])->default('pending');

            //Position
            $table->unsignedInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('monitored_positions');

            //Timestamps and status
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('o_t_s_sessions');
    }
}
