<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_submissions', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->string('assigned_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback_submissions', function (Blueprint $table) {
            $table->dropColumn(['status']);
            $table->dropColumn(['assigned_user']);
        });
    }
};
