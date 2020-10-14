<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->date('year');
            $table->string('goal');
            $table->string('kpi');
            $table->string('activities');
            $table->string('responsibility');
            $table->date('deadline');
            $table->string('resources');
            $table->string('analysis');
            $table->unsignedBigInteger('standard_id');
            $table->timestamps();
            $table->foreign('standard_id')->references('id')->on('standards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
