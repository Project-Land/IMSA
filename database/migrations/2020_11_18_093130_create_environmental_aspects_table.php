<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentalAspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environmental_aspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('process');
            $table->string('waste');
            $table->string('aspect');
            $table->string('influence');
            $table->boolean('waste_type');
            $table->integer('probability_of_appearance');
            $table->integer('probability_of_discovery');
            $table->integer('severity_of_consequences');
            $table->integer('estimated_impact');
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
        Schema::dropIfExists('environmental_aspects');
    }
}
