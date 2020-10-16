<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalCheckReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_check_reports', function (Blueprint $table) {
            $table->id();
            $table->string('specification');
            $table->unsignedBigInteger('internal_check_id');
            $table->foreign('internal_check_id')->references('id')->on('internal_checks');
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
        Schema::dropIfExists('internal_check_reports');
    }
}
