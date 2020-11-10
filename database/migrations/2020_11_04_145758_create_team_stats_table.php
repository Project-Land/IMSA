<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTeamStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->datetime('check_date');
            $table->integer('total');
            $table->timestamps();
        });

        DB::unprepared(
            "CREATE DEFINER=`root`@`localhost` EVENT `team_user_stats` ON SCHEDULE EVERY 1 WEEK STARTS NOW() ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO team_stats (team_id, check_date, total, created_at) SELECT teams.id as team_id, NOW(), count(team_user.user_id) as total, NOW() FROM teams INNER JOIN team_user on teams.id = team_user.team_id GROUP BY teams.id"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_stats');
    }
}
