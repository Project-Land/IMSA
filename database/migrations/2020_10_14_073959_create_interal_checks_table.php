<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteralChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interal_checks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('sector');
            $table->string('leaders');
            $table->unsignedBigInteger('standard_id');
            $table->integer('num_plan_ip');
            $table->string('reports');
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
        Schema::dropIfExists('interal_checks');
    }
}
