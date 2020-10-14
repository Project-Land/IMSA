<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_managements', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->integer('probability');
            $table->integer('frequency');
            $table->integer('total');
            $table->integer('acceptable');
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
        Schema::dropIfExists('risk_managements');
    }
}
