<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInconsistenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inconsistencies', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('internal_check_report_id');
            $table->foreign('internal_check_report_id')->references('id')->on('internal_check_reports');
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('internal_check_report_id');
            $table->foreign('internal_check_report_id')->references('id')->on('internal_check_reports');
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
        Schema::dropIfExists('inconsistencies');
        Schema::dropIfExists('recommendations');
    }
}
