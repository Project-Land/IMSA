<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTableStandardsTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_team', function (Blueprint $table) {
            $table->unsignedbigInteger('standard_id')->nullable();
            $table->unsignedbigInteger('team_id')->nullable();
            $table->foreign('standard_id')->references('id')->on('standards')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->primary(['team_id', 'standard_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standard_team');
    }
}
