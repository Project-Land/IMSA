<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplianceCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_corrections', function (Blueprint $table) {
            $table->id();
            $table->string('document_name');
            $table->string('version');
            $table->string('file_name');
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
        Schema::dropIfExists('compliance_corrections');
    }
}
