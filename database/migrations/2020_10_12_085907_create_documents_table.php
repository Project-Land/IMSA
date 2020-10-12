<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('doc_name');
            $table->string('version');
            $table->enum('doc_category', ['rules_procedure', 'policy', 'procedure', 'manual', 'form']);
            $table->enum('allowed_type', ['word/excel', 'pdf']);
            $table->tinyInteger('can_download');
            $table->tinyInteger('visitor_can_download');
            $table->string('file_name');
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
        Schema::dropIfExists('documents');
    }
}
