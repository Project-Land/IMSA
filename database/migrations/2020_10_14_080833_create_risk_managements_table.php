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
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->integer('probability');
            $table->integer('frequency');
            $table->integer('total');
            $table->integer('acceptable');
            $table->string('measure')->nullable();
            $table->timestamp('measure_created_at')->nullable();
            $table->string('cause')->nullable();
            $table->string('risk_lowering_measure')->nullable();
            $table->string('responsibility')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('risk_managements');
    }
}
