<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateARowInUsersToComplyWithNewCoC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->boolean('display_cid_only')->default(false);
            $table->string('display_fname')->nullable();
            $table->boolean('display_last_name')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('display_cid_only');
            $table->dropColumn('display_fname');
            $table->dropColumn('display_last_name');
        });
    }
}
