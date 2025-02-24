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
            $table->integer('is_student')->nullable();
            $table->integer('is_instructing')->nullable();
            $table->integer('is_ctp')->nullable();
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
            $table->dropColumm(['is_ctp']);
        });
    }
};
