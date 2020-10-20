<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementSystemReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_system_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('standard_id');
            $table->year('year');
            $table->text('participants');
            $table->text('measures_status');
            $table->text('internal_external_changes');
            $table->text('customer_satisfaction');
            $table->string('objectives_scope');
            $table->string('inconsistancies_corrective_measures');
            $table->text('monitoring_measurement_results');
            $table->string('checks_results');
            $table->string('external_suppliers_performance');
            $table->text('resource_adequacy');
            $table->string('measures_effectiveness');
            $table->text('improvement_opportunities');
            $table->text('needs_for_change');
            $table->text('needs_for_resources');
            $table->timestamps();

            $table->foreign('standard_id')->references('id')->on('standards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_system_reviews');
    }
}
