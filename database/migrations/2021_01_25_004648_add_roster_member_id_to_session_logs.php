<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRosterMemberIdToSessionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_logs', function (Blueprint $table) {
            $table->unsignedInteger('roster_member_id')->nullable();
            $table->foreign('roster_member_id')->references('id')->on('roster_members');
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
            $table->dropColumn('roster_member_id');
        });
    }
}
