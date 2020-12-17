<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->boolean('personal_team');
            $table->string('lang', 3)->nullable();
            $table->timestamps();
        });

        DB::table('teams')->insert(
            array(
                'user_id' => '1',
                'name' => 'Default Company',
                'logo' => "",
                'personal_team' => false,
                'lang' => 'sr'
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
        /*Schema::table('teams', function (Blueprint $table){
            $table->dropIndex(['user_id']);
        });*/

        //Schema::drop('teams');
    }
}
