<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTestAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('test_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('test_result_id')->nullable();
            $table->foreign('test_result_id', 'test_result_fk_10162500')->references('id')->on('test_results');
            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id', 'question_fk_10162501')->references('id')->on('questions');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id', 'option_fk_10162502')->references('id')->on('question_options');
        });
    }

    public function down()
    {
        Schema::table('test_answers', function (Blueprint $table) {
            $table->dropForeign('test_result_fk_10162500');
            $table->dropForeign('question_fk_10162501');
            $table->dropForeign('option_fk_10162502');
            $table->dropColumn(['test_result_id', 'question_id', 'option_id']);
        });
    }
}
