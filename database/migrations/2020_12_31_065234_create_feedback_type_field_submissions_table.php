<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTypeFieldSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_type_field_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('feedback_types');
            $table->foreignId('submission_id')->constrained('feedback_submissions');
            $table->string('name');
            $table->text('content');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_type_field_submissions');
    }
}
