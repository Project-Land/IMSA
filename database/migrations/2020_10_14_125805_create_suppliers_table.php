<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->text('subject');
            $table->integer('quality');
            $table->integer('price');
            $table->integer('shippment_deadline');
            $table->boolean('status');
            $table->timestamp('deadline_date');
            $table->unsignedBigInteger('standard_id');
            $table->unsignedBigInteger('team_id');
            $table->timestamps();

            $table->foreign('standard_id')->references('id')->on('standards');
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
