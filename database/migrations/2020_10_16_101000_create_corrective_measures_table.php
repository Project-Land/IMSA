<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectiveMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrective_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained();
            $table->foreignId('internal_check_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('noncompliance_source');
            $table->string('noncompliance_description',500);
            $table->string('noncompliance_cause',500);
            $table->date('noncompliance_cause_date');
            $table->string('measure',500);
            $table->date('measure_date');
            $table->boolean('measure_approval');
            $table->string('measure_approval_reason',500)->nullable();
            $table->date('measure_approval_date')->nullable();
            $table->boolean('measure_status');
            $table->boolean('measure_effective')->nullable();
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
        Schema::dropIfExists('corrective_measures');
    }
}
