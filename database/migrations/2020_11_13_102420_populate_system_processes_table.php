<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateSystemProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('system_processes')->insert(
            array(
                ['name' => 'Upravljanje rizikom', 'route' => 'risk-management', 'display_order' => '1'],
                ['name' => 'Interne provere', 'route' => 'internal-check', 'display_order' => '2'],
                ['name' => 'Neusaglašenosti i korektivne mere ', 'route' => 'corrective-measures', 'display_order' => '3'],
                ['name' => 'Obuke ', 'route' => 'trainings', 'display_order' => '4'],
                ['name' => 'Ciljevi ', 'route' => 'goals', 'display_order' => '5'],
                ['name' => 'Odobreni isporučioci ', 'route' => 'suppliers', 'display_order' => '6'],
                ['name' => 'Zainteresovane strane ', 'route' => 'stakeholders', 'display_order' => '7'],
                ['name' => 'Upravljanje reklamacijama ', 'route' => 'complaints', 'display_order' => '8'],
                ['name' => 'Merna oprema ', 'route' => 'measuring-equipment', 'display_order' => '9'],
                ['name' => 'Preispitivanje sistema menadžmenta ', 'route' => 'management-system-reviews', 'display_order' => '10'],
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
        //
    }
}
