<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('submission_date');
            $table->text('description');
            $table->string('process');
            $table->boolean('accepted');
            $table->date('deadline_date')->nullable();
            $table->string('responsible_person')->nullable();
            $table->string('way_of_solving')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('complaints');
    }
}
