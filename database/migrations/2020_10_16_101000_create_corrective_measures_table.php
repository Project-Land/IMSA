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
            $table->foreignId('inconsistency_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('noncompliance_source');
            $table->text('noncompliance_description');
            $table->text('noncompliance_cause');
            $table->date('noncompliance_cause_date');
            $table->text('measure');
            $table->date('measure_date');
            $table->boolean('measure_approval');
            $table->text('measure_approval_reason')->nullable();
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
