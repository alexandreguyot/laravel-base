<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToQuestionOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id', 'question_fk_10162486')->references('id')->on('questions');
        });
    }

    public function down()
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->dropForeign('question_fk_10162486');
            $table->dropColumn(['question_id']);
        });
    }
}
