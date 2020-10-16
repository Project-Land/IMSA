<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['Interna', 'Eksterna']);
            $table->integer('num_of_employees');
            $table->timestamp('training_date');
            $table->string('place');
            $table->text('resources');
            $table->integer('final_num_of_employees')->nullable();
            $table->integer('rating')->nullable();
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
        Schema::dropIfExists('trainings');
    }
}
