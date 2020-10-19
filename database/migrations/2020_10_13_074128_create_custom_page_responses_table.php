<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPageResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_page_responses', function (Blueprint $table) {
            //Id
            $table->id();

            //Page
            $table->unsignedBigInteger('page_id');
            $table->foreign('page_id')->references('id')->on('custom_pages');

            //User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Content
            $table->longText('content');

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
        Schema::dropIfExists('custom_page_responses');
    }
}
