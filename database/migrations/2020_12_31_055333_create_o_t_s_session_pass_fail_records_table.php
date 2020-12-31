<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOTSSessionPassFailRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_t_s_session_pass_fail_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ots_session_id')->constrained('o_t_s_sessions');
            $table->enum('result', ['passed', 'failed', 'pending'])->default('pending');
            $table->unsignedBigInteger('assessor_id');
            $table->foreign('assessor_id')->references('id')->on('instructors');
            $table->string('report_url')->nullable();
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('o_t_s_session_pass_fail_records');
    }
}
