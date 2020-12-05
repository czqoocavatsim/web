<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_pages', function (Blueprint $table) {
            //Id
            $table->id();

            //Identification
            $table->string('name');
            $table->string('slug');

            //Content
            $table->longText('content');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();

            //Response form
            $table->boolean('response_form_enabled');
            $table->string('response_form_email')->nullable();
            $table->text('response_form_title')->nullable();
            $table->text('response_form_description')->nullable();

            //Timestamp
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
        Schema::dropIfExists('custom_pages');
    }
}
