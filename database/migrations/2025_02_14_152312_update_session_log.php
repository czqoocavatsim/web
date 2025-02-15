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
        Schema::table('session_logs', function (Blueprint $table) {
            $table->string('is_student')->nullable();
            $table->string('is_instructing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_logs', function (Blueprint $table) {
            $table->dropColumn(['is_student']);
            $table->dropColumn(['is_instructing']);
        });
    }
};
