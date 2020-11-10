<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStandardTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_team', function (Blueprint $table) {
            $table->foreignId('standard_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['standard_id', 'team_id']);
        });

        DB::table('standards')->insert(
            array(
                ['name' => '9001'],
                ['name' => '14001'],
                ['name' => '45001']
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
        Schema::dropIfExists('standard_team');
    }
}
