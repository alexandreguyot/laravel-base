<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCrmDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('crm_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id', 'customer_fk_10162384')->references('id')->on('crm_customers');
        });
    }

    public function down()
    {
        Schema::table('crm_documents', function (Blueprint $table) {
            $table->dropForeign('customer_fk_10162384');
            $table->dropColumn(['customer_id']);
        });
    }
}
