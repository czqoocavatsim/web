<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditStaffMembersTableGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_member', function(Blueprint $table) {
             $table->unsignedInteger('group_id')->nullable();
             $table->foreign('group_id')->references('id')->on('staff_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_member', function(Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
