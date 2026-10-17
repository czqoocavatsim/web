<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEventsToEventSignups extends Migration
{
    public function up()
    {
        Schema::create('event_signup_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120);
            $table->date('event_date')->nullable();
            $table->boolean('is_open')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('event_signup_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
            $table->foreign('event_id')->references('id')->on('event_signup_events')->onDelete('cascade');
        });

        Schema::table('event_signups', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
            $table->foreign('event_id')->references('id')->on('event_signup_events')->onDelete('set null');
        });

        if (DB::table('event_signup_slots')->exists() || DB::table('event_signups')->exists()) {
            $eventId = DB::table('event_signup_events')->insertGetId([
                'name' => 'Previous signups',
                'is_open' => false,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('event_signup_slots')->whereNull('event_id')->update(['event_id' => $eventId]);
            DB::table('event_signups')->whereNull('event_id')->update(['event_id' => $eventId]);
        }
    }

    public function down()
    {
        Schema::table('event_signups', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        Schema::table('event_signup_slots', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        Schema::dropIfExists('event_signup_events');
    }
}
