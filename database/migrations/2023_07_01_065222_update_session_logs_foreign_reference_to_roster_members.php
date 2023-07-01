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
            $table->dropForeign('session_logs_roster_member_id_foreign');
            $table->foreign('roster_member_id')->references('id')->on('roster_members')->onDelete('cascade');
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
            $table->dropForeign('session_logs_roster_member_id_foreign');
        });
    }
};
