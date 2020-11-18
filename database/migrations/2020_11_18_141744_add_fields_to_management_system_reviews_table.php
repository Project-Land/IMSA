<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToManagementSystemReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_system_reviews', function (Blueprint $table) {
            $table->string('communication_and_objections', 500)->nullable();
            $table->string('cae', 500)->nullable();
            $table->string('continous_improvement_opportunities', 500)->nullable();
            $table->string('measures', 500)->nullable();
            $table->string('opportunities', 500)->nullable();
            $table->string('measures_optional', 500)->nullable();
            $table->string('consequences', 500)->nullable();
            $table->string('environmental_aspects')->nullable();
            $table->string('fulfillment_of_obligations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_system_reviews', function (Blueprint $table) {
            //
        });
    }
}
