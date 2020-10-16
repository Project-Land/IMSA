<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_checks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('sector');
            $table->string('leaders');
            $table->unsignedBigInteger('standard_id')->nullable();
            $table->unsignedBigInteger('plan_ip_id')->nullable();
            $table->unsignedBigInteger('internal_check_report_id')->nullable();
            $table->timestamps();
            $table->foreign('standard_id')->references('id')->on('standards');
            $table->foreign('internal_check_report_id')->references('id')->on('internal_checks');
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
