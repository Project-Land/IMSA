<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained();
            $table->foreignId('plan_ip_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('internal_check_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('leaders');
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
        Schema::dropIfExists('interal_checks');
    }
}
