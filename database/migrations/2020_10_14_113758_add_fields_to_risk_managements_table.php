<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToRiskManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_managements', function (Blueprint $table) {
            $table->string('measure')->nullable();
            $table->timestamps('measure_created_at')->nullable();
            $table->string('cause')->nullable();
            $table->string('risk_lowering_measure')->nullable();
            $table->string('responsibility')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_managements', function (Blueprint $table) {
            //
        });
    }
}
