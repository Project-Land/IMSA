<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignPlanIpsIdOnInternalChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internal_checks', function (Blueprint $table) {
            $table->foreign('plan_ip_id')->references('id')->on('plan_ips');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign('internal_checks_plan_ips_id_foreign');
    }
}
