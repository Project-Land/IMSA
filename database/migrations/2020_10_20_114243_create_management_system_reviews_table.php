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
            $table->string('participants', 500)->nullable();
            $table->string('measures_status', 500)->nullable();
            $table->string('internal_external_changes', 500)->nullable();
            $table->string('customer_satisfaction', 500)->nullable();
            $table->string('objectives_scope')->nullable();
            $table->string('inconsistancies_corrective_measures')->nullable();
            $table->string('monitoring_measurement_results')->nullable();
            $table->string('checks_results')->nullable();
            $table->string('checks_results_desc', 500)->nullable();
            $table->string('external_suppliers_performance')->nullable();
            $table->string('resource_adequacy', 500);
            $table->string('measures_effectiveness')->nullable();
            $table->string('improvement_opportunities', 500)->nullable();
            $table->string('needs_for_change', 500)->nullable();
            $table->string('needs_for_resources', 500)->nullable();
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
