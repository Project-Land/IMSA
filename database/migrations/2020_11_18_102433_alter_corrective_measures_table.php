<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCorrectiveMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective_measures', function (Blueprint $table) {
            $table->dropColumn('internal_check_report_id');
            $table->unsignedBigInteger('correctiveMeasureable_id')->nullable();
            $table->string('correctiveMeasureable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('evaluation_of_legal_and_other_requirements', function (Blueprint $table) {
            $table->dropColumn('correctiveMeasureable_id');
            $table->dropColumn('correctiveMeasureable_type');
        });*/
    }
}
