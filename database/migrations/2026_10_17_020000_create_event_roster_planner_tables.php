<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRosterPlannerTables extends Migration
{
    public function up()
    {
        Schema::table('event_signup_events', function (Blueprint $table) {
            $table->dateTime('starts_at')->nullable()->after('event_date');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
            $table->json('staffing_requirements')->nullable()->after('sort_order');
            $table->boolean('roster_published')->default(false)->after('staffing_requirements');
        });

        Schema::table('event_signups', function (Blueprint $table) {
            $table->json('availability_windows')->nullable()->after('availability');
        });

        Schema::create('event_signup_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->enum('sector', ['Gander', 'Shanwick']);
            $table->string('callsign', 120);
            $table->boolean('is_open')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('event_signup_events')->onDelete('cascade');
        });

        Schema::create('event_signup_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('signup_id');
            $table->unsignedBigInteger('position_id');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('event_signup_events')->onDelete('cascade');
            $table->foreign('signup_id')->references('id')->on('event_signups')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('event_signup_positions')->onDelete('cascade');
            $table->index(['event_id', 'starts_at', 'ends_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_signup_assignments');
        Schema::dropIfExists('event_signup_positions');

        Schema::table('event_signups', function (Blueprint $table) {
            $table->dropColumn('availability_windows');
        });

        Schema::table('event_signup_events', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'ends_at', 'staffing_requirements', 'roster_published']);
        });
    }
}
