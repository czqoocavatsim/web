<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyToRosterMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roster', function (Blueprint $table) {
            $table->float('currency')->nullable(); // Nullable because otherwise bad stuff would happen to the existing table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roster', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
}
