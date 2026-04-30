<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToContactContactsTable extends Migration
{
    public function up()
    {
        Schema::table('contact_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id', 'company_fk_10162433')->references('id')->on('contact_companies');
        });
    }

    public function down()
    {
        Schema::table('contact_contacts', function (Blueprint $table) {
            $table->dropForeign('company_fk_10162433');
            $table->dropColumn(['company_id']);
        });
    }
}
