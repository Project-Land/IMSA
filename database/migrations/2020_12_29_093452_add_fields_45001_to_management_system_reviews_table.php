<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields45001ToManagementSystemReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_system_reviews', function (Blueprint $table) {
            $table->string('incidents', 500)->nullable();
            $table->string('consulting_and_employee_participation', 500)->nullable();
            $table->string('relevant_communication_with_stakeholders', 500)->nullable();
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
