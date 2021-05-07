<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accidents', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('complaints', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('corrective_measures', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('customer_satisfaction', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('environmental_aspects', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('evaluation_of_legal_and_other_requirements', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('goals', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('internal_checks', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('internal_check_reports', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('management_system_reviews', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('measuring_equipment', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('risk_managements', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('sectors', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('soas', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('trainings', function (Blueprint $table) {
            $table->softDeletes();
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('all', function (Blueprint $table) {
            //
        });
    }
}
