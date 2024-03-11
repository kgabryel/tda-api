<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsNotesTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_notes', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->unsignedBigInteger('note_id');
            $table->foreign('note_id')
                ->references('id')
                ->on('notes')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_notes');
    }
}
