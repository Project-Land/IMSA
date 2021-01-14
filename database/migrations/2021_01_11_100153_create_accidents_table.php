<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('injury_type');
            $table->string('jobs_and_tasks_he_performs',500);
            $table->dateTime('injury_datetime');
            $table->string('injury_cause',500);
            $table->string('injury_description',500);
            $table->string('error',500);
            $table->string('order_from');
            $table->string('dangers_and_risks',500);
            $table->string('protective_equipment',500);
            $table->string('high_risk_jobs',500);
            $table->string('job_requirements',500);
            $table->string('supervisor');
            $table->string('witness',500)->nullable();
            $table->dateTime('injury_report_datetime');
            $table->string('comment',500)->nullable();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('accidents');
    }
}
