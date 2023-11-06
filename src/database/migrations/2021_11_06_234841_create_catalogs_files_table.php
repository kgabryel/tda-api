<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsFilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_files', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'file_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_files');
    }
}
