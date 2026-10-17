<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSignupTables extends Migration
{
    public function up()
    {
        Schema::create('event_signup_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label', 120);
            $table->boolean('is_open')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('event_signups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name', 120);
            $table->string('cid', 20);
            $table->enum('preferred_position', ['Gander', 'Shanwick', 'Either position/NAT']);
            $table->json('availability');
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_signups');
        Schema::dropIfExists('event_signup_slots');
    }
}
