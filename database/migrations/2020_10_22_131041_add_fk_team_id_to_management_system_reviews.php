<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkTeamIdToManagementSystemReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_system_reviews', function (Blueprint $table) {
            $table->unsignedbigInteger('user_id')->nullable();
            $table->unsignedbigInteger('team_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_system_reviews', function (Blueprint $table) {
            $table->dropIndex(['user_id','team_id']); 
            $table->dropColumn('user_id');
            $table->dropColumn('team_id');
        });
    }
}
