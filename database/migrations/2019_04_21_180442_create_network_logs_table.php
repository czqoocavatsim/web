<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vatsim_cid');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('level')->default(0);
            $table->text('message');
            $table->dateTime('recorded_at');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('network_logs');
    }
}
