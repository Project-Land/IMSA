<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationOfLegalAndOtherRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_of_legal_and_other_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('requirement_level');
            $table->string('document_name');
            $table->boolean('compliance');
            $table->string('note')->nullable();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('evaluation_of_legal_and_other_requirements');
    }
}
