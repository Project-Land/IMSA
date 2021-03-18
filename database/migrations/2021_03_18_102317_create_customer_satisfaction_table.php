<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_satisfaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('customer');
            $table->string('col1', 15)->nullable();
            $table->string('col2', 15)->nullable();
            $table->string('col3', 15)->nullable();
            $table->string('col4', 15)->nullable();
            $table->string('col5', 15)->nullable();
            $table->string('col6', 15)->nullable();
            $table->string('col7', 15)->nullable();
            $table->string('col8', 15)->nullable();
            $table->string('col9', 15)->nullable();
            $table->string('col10', 15)->nullable();
            $table->string('comment', 512)->nullable();
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
        Schema::dropIfExists('customer_satisfaction');
    }
}
