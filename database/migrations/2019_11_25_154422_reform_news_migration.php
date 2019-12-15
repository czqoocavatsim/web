<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReformNewsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('news');
        Schema::create('news', function (Blueprint $table) {
           $table->increments('id')->unsigned();
           $table->text('title');
           $table->integer('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users');
           $table->boolean('show_author')->default(false);
           $table->text('image')->nullable();
           $table->longText('content')->nullable();
           $table->text('summary')->nullable();
           $table->dateTime('published');
           $table->dateTime('edited')->nullable();
           $table->boolean('visible')->default(true);
           $table->integer('email_level')->default(0);
           $table->boolean('certification')->default(false);
           $table->string('slug');
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
        Schema::dropIfExists('news');
    }
}
