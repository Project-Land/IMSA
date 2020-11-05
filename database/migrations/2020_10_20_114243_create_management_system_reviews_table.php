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
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->text('participants');
            $table->text('measures_status');
            $table->text('internal_external_changes');
            $table->text('customer_satisfaction');
            $table->string('objectives_scope');
            $table->string('inconsistancies_corrective_measures');
            $table->text('monitoring_measurement_results');
            $table->string('checks_results');
            $table->text('checks_results_desc')->nullable();
            $table->string('external_suppliers_performance');
            $table->text('resource_adequacy');
            $table->string('measures_effectiveness');
            $table->text('improvement_opportunities')->nullable();
            $table->text('needs_for_change')->nullable();
            $table->text('needs_for_resources')->nullable();
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
        Schema::dropIfExists('management_system_reviews');
    }
}
