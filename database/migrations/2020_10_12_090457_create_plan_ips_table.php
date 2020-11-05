<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_ips', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('checked_date')->nullable();
            $table->string('checked_sector')->nullable();
            $table->string('standard')->nullable();
            $table->string('team_for_internal_check')->nullable();
            $table->dateTime('check_start')->nullable();
            $table->dateTime('check_end')->nullable();
            $table->date('report_deadline')->nullable();
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
        //Schema::dropIfExists('plan_ips');
    }
}
