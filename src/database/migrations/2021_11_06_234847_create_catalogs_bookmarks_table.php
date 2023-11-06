<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsBookmarksTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_bookmarks', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->unsignedBigInteger('bookmark_id');
            $table->foreign('bookmark_id')
                ->references('id')
                ->on('bookmarks')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'bookmark_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_bookmarks');
    }
}
