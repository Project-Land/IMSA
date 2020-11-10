<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_global')->default(0);
            $table->timestamps();
        });

        DB::table('sectors')->insert(
            array(
                'user_id' => '1',
                'team_id' => '1',
                'name' => 'Sistem Menadžmenta',
                'is_global' => true
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('sectors');
    }

}
