<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('fname')->default(null);
            $table->string('lname')->default(null);
            $table->string('email')->default(null);
            $table->string('rating')->default(null);
            $table->string('division')->default(null);
            /*
             * 0 = Guest
             * 1 = CZQO controller
             * 2 = Instructor
             * 3 = Directors
             * 4 = Full
             */
            $table->unsignedInteger('permissions')->default(0);
            $table->boolean('deleted')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
