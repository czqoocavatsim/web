<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_tickets_targets', function (Blueprint $table) {
            //ID
            $table->id();

            //Role
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');

            //Info
            $table->string('label');

            //Enabled
            $table->boolean('enabled')->default(true);
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            //ID
            $table->id();
            $table->string('slug');

            //User assignment
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Status
            $table->boolean('open')->default(true);

            //Info
            $table->string('subject');

            //Target
            $table->unsignedBigInteger('target_id');
            $table->foreign('target_id')->references('id')->on('support_tickets_targets');

            //timestamps and status
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
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_tickets_targets');
    }
}
