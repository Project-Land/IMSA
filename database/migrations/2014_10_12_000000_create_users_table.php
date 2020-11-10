<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'name' => 'Super Admin',
                'username' => 'super-admin',
                'email' => null,
                'email_verified_at' => null,
                'password' => '$2y$10$AqP9.RzKlK5cZ3rDJuf9ReOaxp5xSB8vZl/T6XaIhFN.KfD2nHkpW',
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'remember_token' => null,
                'current_team_id' => 1,
                'profile_photo_path' => null
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
        //Schema::dropIfExists('users');
    }
}
