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
            $table->string('name');
            $table->unsignedBigInteger('standard_id');
            $table->string('noncompliance_source');
            $table->unsignedBigInteger('sector_id');
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

            $table->foreign('standard_id')->references('id')->on('standards');
            $table->foreign('sector_id')->references('id')->on('sectors');
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
