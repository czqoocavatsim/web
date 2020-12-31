<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryEmailSentToSoloCerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solo_certifications', function (Blueprint $table) {
            $table->boolean('expiry_notification_sent')->default(false);
            $table->dateTime('expiry_notification_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solo_certifications', function (Blueprint $table) {
            $table->dropColumn('expiry_notification_sent');
            $table->dropColumn('expiry_notification_time');
        });
    }
}
