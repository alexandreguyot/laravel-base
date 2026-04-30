<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCrmCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id', 'status_fk_10162367')->references('id')->on('crm_statuses');
        });
    }

    public function down()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->dropForeign('status_fk_10162367');
            $table->dropColumn(['status_id']);
        });
    }
}
