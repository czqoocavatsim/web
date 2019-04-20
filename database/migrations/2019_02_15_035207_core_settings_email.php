<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoreSettingsEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_info', function($table) {
            $table->text('emailfirchief');
            $table->text('emaildepfirchief');
            $table->text('emailcinstructor');
            $table->text('emaileventc');
            $table->text('emailfacilitye');
            $table->text('emailwebmaster');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_info', function($table) {
            $table->dropColumn('emailfirchief');
            $table->dropColumn('emaildepfirchief');
            $table->dropColumn('emailcinstructor');
            $table->dropColumn('emaileventc');
            $table->dropColumn('emailfacilitye');
            $table->dropColumn('emailwebmaster');
        });
    }
}
