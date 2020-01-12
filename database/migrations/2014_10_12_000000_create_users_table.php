<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('rating_id')->default(null)->nullable();
            $table->string('rating_short')->default(null)->nullable();
            $table->string('rating_long')->default(null)->nullable();
            $table->string('rating_GRP')->default(null)->nullable();
            $table->dateTime('reg_date')->default(null)->nullable();
            $table->string('region_code')->default(null)->nullable();
            $table->string('region_name')->default(null)->nullable();
            $table->string('division_code')->default(null)->nullable();
            $table->string('division_name')->default(null)->nullable();
            $table->string('subdivision_code')->default(null)->nullable();
            $table->string('subdivision_name')->default(null)->nullable();
            $table->unsignedInteger('permissions')->default(0);
            $table->integer('gdpr_subscribed_emails')->default(0);
            $table->boolean('deleted')->default(false);
            $table->integer('init')->default(0);
            $table->string('avatar')->default('/img/default-profile-img.jpg');
            $table->longText('bio')->nullable();
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
