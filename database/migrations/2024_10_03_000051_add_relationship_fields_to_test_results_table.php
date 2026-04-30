<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTestResultsTable extends Migration
{
    public function up()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->unsignedBigInteger('test_id')->nullable();
            $table->foreign('test_id', 'test_fk_10162493')->references('id')->on('tests');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id', 'student_fk_10162494')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->dropForeign('test_fk_10162493');
            $table->dropForeign('student_fk_10162494');
            $table->dropColumn(['test_id', 'student_id']);
        });
    }
}
