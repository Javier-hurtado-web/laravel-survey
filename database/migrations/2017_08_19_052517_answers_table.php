<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('answers', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->unsignedInteger('survey_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('question_option_id');
            $table->text('free_text');
            $table->timestamps();
        });

        Schema::table('answers', function(Blueprint $table) {
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        });

        Schema::table('answers', function(Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });

        Schema::table('answers', function(Blueprint $table) {
            $table->foreign('question_option_id')->references('id')->on('questions_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function(Blueprint $table) {
            $table->dropForeign(['survey_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['question_option_id']);
        });
        Schema::dropIfExists('answers');
    }
}
