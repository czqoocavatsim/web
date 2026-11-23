<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExamCentreTables extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('active')->default(true);
            $table->unsignedTinyInteger('pass_percentage')->default(80);
            $table->unsignedSmallInteger('questions_to_ask')->default(20); // 0 = all questions
            $table->unsignedSmallInteger('initial_retake_days')->default(1);
            $table->unsignedSmallInteger('subsequent_retake_days')->default(7);
            $table->unsignedSmallInteger('time_limit_minutes')->nullable(); // null = untimed
            $table->timestamps();
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_id');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->text('question');
            $table->boolean('required')->default(false); // always included in the paper
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('exam_question_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_question_id');
            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
            $table->string('option_text', 1000);
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_id');
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->unsignedInteger('user_id')->index();
            $table->string('status', 20)->default('in_progress'); // in_progress | passed | failed
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->unsignedSmallInteger('correct_count')->nullable();
            $table->unsignedSmallInteger('total_questions')->nullable();
            $table->decimal('score_percent', 5, 2)->nullable();
            $table->unsignedTinyInteger('pass_percentage'); // pass mark in force when taken
            $table->timestamps();
        });

        Schema::create('exam_attempt_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_attempt_id');
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');
            $table->unsignedInteger('exam_question_id');
            $table->foreign('exam_question_id')->references('id')->on('exam_questions');
            $table->unsignedSmallInteger('position');
            $table->unsignedInteger('exam_question_option_id')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });

        // The exam we run today on VATCAN, with the same settings.
        DB::table('exams')->insert([
            'name' => 'Gander OCA Entrance Exam',
            'slug' => 'gander-oca-entrance-exam',
            'active' => false, // switch on once the question bank is imported
            'pass_percentage' => 80,
            'questions_to_ask' => 20,
            'initial_retake_days' => 1,
            'subsequent_retake_days' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('exam_attempt_answers');
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_question_options');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
    }
}
