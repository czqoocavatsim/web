<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('slug');

            //User assignment
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            //Feedback type
            $table->foreignId('type_id')->constrained('feedback_types');

            //Feedback content
            $table->text('submission_content');
            $table->boolean('permission_to_publish')->default(false);

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
        Schema::dropIfExists('feedback_submissions');
    }
}
