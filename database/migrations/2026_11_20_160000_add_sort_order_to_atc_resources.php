<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('atc_resources', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('url');
        });

        // Keep today's order: number the existing resources 10, 20, 30... by id.
        $n = 10;
        foreach (DB::table('atc_resources')->orderBy('id')->pluck('id') as $id) {
            DB::table('atc_resources')->where('id', $id)->update(['sort_order' => $n]);
            $n += 10;
        }
    }

    public function down()
    {
        Schema::table('atc_resources', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
